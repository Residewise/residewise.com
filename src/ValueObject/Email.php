<?php

namespace App\ValueObject;

final class Email
{
    public final const NO_REPLY_APP_EMAIL = 'no-reply@residewise.com';
    public final const INFO_APP_EMAIL = 'info@residewise.com';
    public final const CONFIRM_EMAIL_SUBJECT = 'Email confirmation';
    public final const CONFIRM_EMAIL_TEMPLATE = 'emails/signup.html.twig';
    private const CONFIRM_EMAIL_LINK = 'email/confirm/';

    public static function getConfirmationLinkFromToken(string $token) : string
    {
       return self::CONFIRM_EMAIL_LINK . $token;
    }
}
