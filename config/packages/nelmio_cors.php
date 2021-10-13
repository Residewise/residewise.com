<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('nelmio_cors', [
        'defaults' => [
            'origin_regex' => false,
            'allow_origin' => ['*'],
            'allow_methods' => ['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE'],
            'allow_headers' => ['Content-Type', 'Authorization'],
            'expose_headers' => ['Link'],
            'max_age' => 3600,
        ],
        'paths' => [
            '^/' =>
                null,
        ],
    ]);
};
