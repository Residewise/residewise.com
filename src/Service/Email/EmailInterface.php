<?php

namespace App\Service\Email;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;

interface EmailInterface
{
    public const SENDER_EMAIL = 'info@residewise.com';

    public function __invoke(string $template, ?User $user) : TemplatedEmail|Email;

    public function send(TemplatedEmail|Email $email) : void;

}
