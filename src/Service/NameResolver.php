<?php

declare(strict_types = 1);

namespace App\Service;

use function explode;

class NameResolver
{
    public function isFullName(string $name): bool
    {
        return count(explode(' ', $name)) >= 2;
    }
}
