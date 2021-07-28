<?php

declare(strict_types=1);

namespace App\Tests;

require \dirname(__DIR__) . '/vendor/autoload.php';
if (\file_exists(\dirname(__DIR__) . '/config/bootstrap.php')) {
    require \dirname(__DIR__) . '/config/bootstrap.php';
} elseif (\method_exists(\Symfony\Component\Dotenv\Dotenv::class, 'bootEnv')) {
    (new \Symfony\Component\Dotenv\Dotenv())->bootEnv(\dirname(__DIR__) . '/.env');
}
