<?php

namespace App\Service;

use function explode;

class NameResolver
{

    public function isFullName(string $name) : bool
    {
        return count(explode(' ', $name)) >= 2;
    }
}
