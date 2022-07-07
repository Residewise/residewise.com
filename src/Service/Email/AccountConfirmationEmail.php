<?php

declare(strict_types = 1);

namespace App\Service\Email;

use App\Entity\User;
use PharIo\Manifest\InvalidEmailException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function PHPUnit\Framework\throwException;

class AccountConfirmationEmail implements EmailInterface
{
    public const EMAIL_TEMPLATE = '/emails/confirm-email-address.html.twig';

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly TranslatorInterface $translator,
        private UrlGeneratorInterface $generator,
    ) {
    }

    public function send(User|UserInterface $user, ?array $options): TemplatedEmail|Email
    {
        $subject = $this->translator->trans('email.confirm.email-address');

        $email = new TemplatedEmail();
        $email->subject($subject);
        $email->sender(EmailInterface::SENDER_EMAIL);
        $email->addTo(new Address($user->getEmail()));
        $email->htmlTemplate(self::EMAIL_TEMPLATE)->context([
            'user' => $user,
            'path' => $this->generator->generate('user_reset_password', [
                'token' => $user->getToken(),
            ]),
        ]);

        try {
            $this->mailer->send($email);
        } catch (InvalidEmailException $invalidEmailException) {
            throwException($invalidEmailException);
        }

        return $email;
    }
}
