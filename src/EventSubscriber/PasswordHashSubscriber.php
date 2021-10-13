<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHashSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordEncoder
    ) {
    }

    /**
     * @return array<string, array<int|string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE],
        ];
    }

    public function hashPassword(ViewEvent $viewEvent): void
    {
        $user = $viewEvent->getControllerResult();
        $request = $viewEvent->getRequest();

        if (! $user instanceof User || ! $request->isMethod(Request::METHOD_POST)) {
            return;
        }

        $user->setPassword(
            $this->passwordEncoder->hashPassword($user, $user->getPassword())
        );
    }
}
