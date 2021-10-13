<?php

declare(strict_types=1);

namespace App\Security;

class TokenGenerator
{
    private const CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function generateToken(int $length = 100): string
    {
        $token = '';
        $alphabetLength = strlen(self::CHARS);

        for ($i = 0; $i < $length; ++$i) {
            $token .= self::CHARS[random_int(0, $alphabetLength - 1)];
        }

        return $token;
    }
}
