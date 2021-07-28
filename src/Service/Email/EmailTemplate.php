<?php

declare(strict_types=1);

namespace App\Service\Email;

class EmailTemplate
{
    public const VERIFY_EMAIL =
  [
    'template' => 'signup.html.twig',
      'subject' => 'Please verify your email address',
];
}
