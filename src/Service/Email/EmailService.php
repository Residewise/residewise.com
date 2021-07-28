<?php

declare(strict_types=1);

namespace App\Service\Email;

use App\Entity\User;
use App\Service\Email\Contract\EmailInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailService implements EmailInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function send(
        User $user,
        string $subject,
        string $template,
        array $context
    ): void {
        $email = new Email();
        $email->from(new Address('no-reply@residewise.com'));
        $email->to(new Address('kerrialbeckettnewham@gmail.com', $user->getFullName()));
        $email->subject($subject);
        $email->html('<p>Lorem ipsum...</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
        }
    }
}
