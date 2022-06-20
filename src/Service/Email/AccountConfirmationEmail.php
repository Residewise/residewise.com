<?php

namespace App\Service\Email;

use App\Entity\User;
use PharIo\Manifest\InvalidEmailException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class AccountConfirmationEmail implements EmailInterface
{

    public function __construct(
        private readonly Mailer $mailer
    ) {
    }

    public function __invoke(
        string $template,
        ?User $user
    ): TemplatedEmail|Email
    {
        $email = new TemplatedEmail();
        $email->sender(EmailInterface::SENDER_EMAIL);
        $email->addTo(new Address($user->getEmail()));
        $email->htmlTemplate($template);

        return $email;
    }

    public function send(TemplatedEmail|Email $email) : void
    {
        try {
            $this->mailer->send($email);
        } catch (InvalidEmailException) {

        }
    }

}
