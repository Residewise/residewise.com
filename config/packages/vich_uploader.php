<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Vich\UploaderBundle\Naming\SmartUniqueNamer;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('vich_uploader', [
        'db_driver' => 'orm',
        'mappings' => [
            'media' => [
                'uri_prefix' => '/media',
                'upload_destination' => '%kernel.project_dir%/public/media',
                'namer' => SmartUniqueNamer::class,
                
                
            ],
        ],
    ]);
};
