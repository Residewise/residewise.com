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
}