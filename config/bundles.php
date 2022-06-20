<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\Bundle\MercureBundle\MercureBundle;
use EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle;
use Symfony\UX\Dropzone\DropzoneBundle;
use KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle;
use Symfony\UX\Chartjs\ChartjsBundle;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Tbbc\MoneyBundle\TbbcMoneyBundle;
return [
    FrameworkBundle::class => ['all' => true],
    SensioFrameworkExtraBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true],
    WebProfilerBundle::class => ['dev' => true, 'test' => true],
    MonologBundle::class => ['all' => true],
    DebugBundle::class => ['dev' => true],
    MakerBundle::class => ['dev' => true],
    DoctrineBundle::class => ['all' => true],
    DoctrineMigrationsBundle::class => ['all' => true],
    SecurityBundle::class => ['all' => true],
    TwigExtraBundle::class => ['all' => true],
    WebpackEncoreBundle::class => ['all' => true],
    TurboBundle::class => ['all' => true],
    MercureBundle::class => ['all' => true],
    EasyAdminBundle::class => ['all' => true],
    DropzoneBundle::class => ['all' => true],
    KnpUOAuth2ClientBundle::class => ['all' => true],
    ChartjsBundle::class => ['all' => true],
    KnpPaginatorBundle::class => ['all' => true],
    TbbcMoneyBundle::class => ['all' => true],
];
