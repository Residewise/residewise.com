FROM ghcr.io/residewise/php:main AS composer

ENV APP_ENV="prod" \
    APP_DEBUG=0 \
    PHP_OPCACHE_PRELOAD="/app/config/preload.php" \
    PHP_EXPOSE_PHP="off" \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0

RUN rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN mkdir -p var/cache var/log

# Intentionally split into multiple steps to leverage docker layer caching
COPY composer.json composer.lock symfony.lock ./

RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts



FROM node:14 as js-builder

WORKDIR /build

# We need /vendor here
COPY --from=composer /app .

# Install npm packages
COPY package.json yarn.lock webpack.config.js ./
RUN yarn install

# Production yarn build
COPY ./assets ./assets

RUN yarn run build



FROM composer as php

COPY --from=js-builder /build .
COPY . .

# Need to run again to trigger scripts with application code present
RUN composer install --no-dev --no-interaction --classmap-authoritative
RUN composer symfony:dump-env prod
RUN chmod -R 777 var



FROM ghcr.io/residewise/caddy:main AS caddy

COPY --from=php /app/public public/
