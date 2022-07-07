<?php

declare(strict_types = 1);

namespace App\Service;

class Base64FileUtil
{
    public function encodeBase64(string $content): string
    {
        return 'data:image/png;base64,' . base64_encode($content);
    }
}
