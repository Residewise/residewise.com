<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class UploadImageAction
{
    public function __invoke(Request $request): bool
    {
        return true;
    }
}
