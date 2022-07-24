<?php

declare(strict_types = 1);

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;

interface EmailInterface
{
    public const SENDER_EMAIL = 'info@residewise.com';

    public function send(User $user, ?array $options): TemplatedEmail|Email;
}
