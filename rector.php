<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig) {
    $rectorConfig->parallel();
    $rectorConfig->importNames();
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/config',
        __DIR__ . '/tests',
    ]);
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');
    $rectorConfig->phpVersion(PhpVersion::PHP_81);

    $rectorConfig->import(SymfonySetList::SYMFONY_CODE_QUALITY);
//     $rectorConfig->import(DoctrineSetList::DOCTRINE_REPOSITORY_AS_SERVICE);
    // $rectorConfig->import(SetList::CODE_QUALITY);
    // $rectorConfig->import(SetList::PHP_81);
    // $rectorConfig->import(SetList::TYPE_DECLARATION);
    // $rectorConfig->import(DoctrineSetList::DOCTRINE_25);
};
