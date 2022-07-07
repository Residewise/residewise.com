<?php

declare(strict_types = 1);

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Bundle\MercureBundle\MercureBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\UX\Autocomplete\AutocompleteBundle;
use Symfony\UX\Chartjs\ChartjsBundle;
use Symfony\UX\Dropzone\DropzoneBundle;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;

use Twig\Extra\TwigExtraBundle\TwigExtraBundle;

return [
    FrameworkBundle::class => [
        'all' => true,
    ],
    SensioFrameworkExtraBundle::class => [
        'all' => true,
    ],
    TwigBundle::class => [
        'all' => true,
    ],
    WebProfilerBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    MonologBundle::class => [
        'all' => true,
    ],
    DebugBundle::class => [
        'dev' => true,
    ],
    MakerBundle::class => [
        'dev' => true,
    ],
    DoctrineBundle::class => [
        'all' => true,
    ],
    DoctrineMigrationsBundle::class => [
        'all' => true,
    ],
    SecurityBundle::class => [
        'all' => true,
    ],
    TwigExtraBundle::class => [
        'all' => true,
    ],
    WebpackEncoreBundle::class => [
        'all' => true,
    ],
    TurboBundle::class => [
        'all' => true,
    ],
    MercureBundle::class => [
        'all' => true,
    ],
    EasyAdminBundle::class => [
        'all' => true,
    ],
    DropzoneBundle::class => [
        'all' => true,
    ],
    KnpUOAuth2ClientBundle::class => [
        'all' => true,
    ],
    ChartjsBundle::class => [
        'all' => true,
    ],
    KnpPaginatorBundle::class => [
        'all' => true,
    ],
    AutocompleteBundle::class => [
        'all' => true,
    ],
];
