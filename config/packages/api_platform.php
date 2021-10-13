<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('api_platform', [
        'mapping' => [
            'paths' => ['%kernel.project_dir%/src/Entity'],
        ],
        'patch_formats' => [
            'json' => ['application/merge-patch+json'],
        ],
        'swagger' => [
            'versions' => [3],
            'api_keys' => [
                'apiKey' => [
                    'name' => 'Authorization',
                    'type' => 'header',
                ],
            ],
        ],
        'title' => 'Residewise API Docs',
        'version' => '0.0.1',
        'show_webby' => false,
    ]);
};
