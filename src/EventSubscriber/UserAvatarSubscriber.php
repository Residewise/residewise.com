<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\AvatarService;
use Hedronium\Avity\Layouts\DiagonalMirror;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Hedronium\Avity\Avity;
use Hedronium\Avity\Generators\Hash;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserAvatarSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly AvatarService $avatarService
    ) {
    }

    /**
     * @return array<string, array<int|string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['setAvatar', EventPriorities::PRE_WRITE],
        ];
    }

    public function setAvatar(ViewEvent $viewEvent): void
    {
        $user = $viewEvent->getControllerResult();
        $method = $viewEvent->getRequest()->getMethod();

        if (! $user instanceof User || $method !== Request::METHOD_POST) {
            return;
        }

        $avatar = $this->avatarService->createAvatar($user->getEmail());
        $user->setAvatar($avatar);
    }
}
