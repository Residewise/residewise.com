<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Security\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function dump;

class TokenSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TokenGenerator $tokenGenerator
    ) {
    }

    /**
     * @return array<string, array<int|string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['generateToken', EventPriorities::PRE_WRITE],
        ];
    }

    public function generateToken(ViewEvent $viewEvent): void
    {
        $user = $viewEvent->getControllerResult();
        $request = $viewEvent->getRequest();

        if (!$user instanceof User || !$request->isMethod(Request::METHOD_POST)) {
            return;
        }

        if (!$user->getToken()) {
            $token = $this->tokenGenerator->generateToken();
            $user->setToken($token);
        }
    }
}
