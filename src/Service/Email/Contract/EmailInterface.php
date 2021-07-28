<?php

declare(strict_types=1);

namespace App\Service\Email\Contract;

use App\Entity\User;

interface EmailInterface
{
    /**
     * @param array<mixed> $context
     */
    public function send(
        User $user,
        string $subject,
        string $template,
        array $context
    ): void;
}
