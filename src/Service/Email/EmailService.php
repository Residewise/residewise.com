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
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    /**
     * @param array<mixed> $context
     */
    public function send(
        User $user,
        string $subject,
        string $template,
        array $context
    ): void {
        $sender = new Address('no-reply@residewise.com', 'Residewise');
        $recipient = new Address('kerrialbeckettnewham@gmail.com', 'Someone');

        $email = new Email();
        $email->from($sender);
        $email->to($recipient);
        $email->subject($subject);
        $email->html('<p>Lorem ipsum...</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
        }
    }
}
