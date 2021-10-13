<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Contract\OwnerEntityInterface;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class OwnedEntitySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Security $security
    ) {
    }

    /**
     * @return array<string, array<int|string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE],
        ];
    }

    public function getAuthenticatedUser(ViewEvent $viewEvent): void
    {
        $entity = $viewEvent->getControllerResult();
        $method = $viewEvent->getRequest()->getMethod();

        if (! $entity instanceof OwnerEntityInterface || $method !== Request::METHOD_POST) {
            return;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        $entity->setOwner($user);
    }
}
