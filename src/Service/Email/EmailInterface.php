<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\User\UserInterface;

interface EmailInterface
{
    public const SENDER_EMAIL = 'info@residewise.com';

    public function send(User|UserInterface $user, ?array $options) : TemplatedEmail|Email;

}
