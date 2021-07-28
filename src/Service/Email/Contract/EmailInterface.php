<?php

declare(strict_types=1);

namespace App\Service\Email\Contract;

use App\Entity\User;

interface EmailInterface
{
    public function send(
        User $user,
        string $subject,
        string $template,
        array $context
    ): void;
}
