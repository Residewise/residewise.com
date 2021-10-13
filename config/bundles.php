<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Nelmio\CorsBundle\NelmioCorsBundle;
use ApiPlatform\Core\Bridge\Symfony\Bundle\ApiPlatformBundle;
use Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Vich\UploaderBundle\VichUploaderBundle;
return [
    FrameworkBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true],
    SecurityBundle::class => ['all' => true],
    DoctrineBundle::class => ['all' => true],
    DoctrineMigrationsBundle::class => ['all' => true],
    NelmioCorsBundle::class => ['all' => true],
    ApiPlatformBundle::class => ['all' => true],
    LexikJWTAuthenticationBundle::class => ['all' => true],
    MakerBundle::class => ['dev' => true],
    WebpackEncoreBundle::class => ['all' => true],
    DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],
    MonologBundle::class => ['all' => true],
    VichUploaderBundle::class => ['all' => true],
];
