<?php

declare(strict_types=1);

use App\Entity\User;
use App\Security\UserEnabledChecker;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('security', [
        'enable_authenticator_manager' => true,
        'encoders' => [
            User::class => [
                'algorithm' => 'auto',
            ],
        ],
        'providers' => [
            'app_user_provider' => [
                'entity' => [
                    'class' => User::class,
                    'property' => 'email',
                ],
            ],
        ],
        'firewalls' => [
            'login' => [
                'pattern' => '^/api/login',
                'stateless' => true,
                'json_login' => [
                    'username_path' => 'email',
                    'check_path' => '/api/login_check',
                    'success_handler' => 'lexik_jwt_authentication.handler.authentication_success',
                    'failure_handler' => 'lexik_jwt_authentication.handler.authentication_failure',
                ],
                'user_checker' => UserEnabledChecker::class,
            ],
            'api' => [
                'pattern' => '^/api',
                'stateless' => true,
                'jwt' => []
            ],
        ],
        'access_control' => [
            [
                'path' => '^/api/docs',
                'roles' => 'PUBLIC_ACCESS',
            ],
            [
                'path' => '^/api/login',
                'roles' => 'PUBLIC_ACCESS',
            ],
            [
                'path' => '^/api/users',
                'methods' => [Request::METHOD_POST],
                'roles' => 'PUBLIC_ACCESS',
            ],
            [
                'path' => '^/api/users/confirmation'
            ],
            [
                'path' => '^/api',
                'roles' => 'IS_AUTHENTICATED_FULLY',
            ]
        ],
    ]);
};
