<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\ValueObject\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRegisterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }

    /**
     * @return array<string, array<int|string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendConfirmationEmail', EventPriorities::PRE_WRITE],
        ];
    }

    public function sendConfirmationEmail(ViewEvent $viewEvent): void
    {
        $user = $viewEvent->getControllerResult();
        $method = $viewEvent->getRequest()
            ->getMethod();

        if (!$user instanceof User || $method !== Request::METHOD_POST) {
            return;
        }

        $email = (new TemplatedEmail())->from(Email::NO_REPLY_APP_EMAIL)
            ->to(new Address($user->getEmail()))
            ->subject(Email::CONFIRM_EMAIL_SUBJECT)
            ->htmlTemplate(Email::CONFIRM_EMAIL_TEMPLATE)
            ->context([
                'fullname' => $user->getFullName(),
                'link' => Email::getConfirmationLinkFromToken($user->getToken())
            ]);
        $this->mailer->send($email);
    }

}
