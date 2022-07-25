<?php

declare(strict_types = 1);

namespace App\Enum;

enum Role : string
{
    case User = 'ROLE_USER';
    case Agent = 'ROLE_AGENT';
    case Owner = 'ROLE_OWNER';
    case Tenant = 'ROLE_TENANT';
    case Service = 'ROLE_SERVICE_PROVIDER';

    public static function getRoles() : array
    {
        return [
            Role::Owner->value,
            Role::Tenant->value,
            Role::Agent->value,
            Role::Service->value,
        ];
    }

    public static function sanitizeRoles(array $roles) : array
    {
        return array_intersect($roles, Role::getRoles());
    }

}