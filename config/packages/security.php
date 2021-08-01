<?php

declare(strict_types=1);

use App\Entity\User;
use App\Security\UserEnabledChecker;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

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
                'user_checker' => UserEnabledChecker
                ::class,
            ],
            'api' => [
                'pattern' => '^/api',
                'stateless' => true,
                'jwt'
                 => null,
            ],
        ],
        'role_hierarchy' => [
            'ROLE_PROPERTY_OWNER' => ['ROLE_SELLER', 'ROLL_BUYER', 'ROLE_LANDLORD', 'ROLE_COMMENTER', 'ROLE_MESSENGER', 'ROLE_RATER'],
            'ROLE_TENANT' => ['ROLE_COMMENTER', 'ROLE_MESSENGER', 'ROLE_RATER'],
            'ROLE_AGENT' => ['ROLE_PROXY_LANDLORD', 'ROLE_PROXY_SELLER'],
            'ROLE_SERVICE_PROVIDER' => ['ROLE_MESSENGER', 'ROLE_MESSENGER'],
        ],
        'access_control' => [[
            'path' => '^/api/docs',
            'roles' => 'IS_AUTHENTICATED_ANONYMOUSLY',
        ], [
            'path' => '^/api/login',
            'roles' => 'IS_AUTHENTICATED_ANONYMOUSLY',
        ], [
            'path' => '^/api/users',
            'roles' => 'IS_AUTHENTICATED_ANONYMOUSLY',
        ], [
            'path' => '^/api',
            'roles' => 'IS_AUTHENTICATED_FULLY',
        ]],
    ]);
};
