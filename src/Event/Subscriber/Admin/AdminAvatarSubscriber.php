<?php

declare(strict_types = 1);

namespace App\Event\Subscriber\Admin;

use App\Entity\User;
use App\Service\AvatarService;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminAvatarSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly AvatarService $avatarService)
    {
    }

    /**
     * @return array<class-string<BeforeEntityPersistedEvent>, string[]>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setAvatar'],
        ];
    }

    public function setAvatar(BeforeEntityPersistedEvent $beforeEntityPersistedEvent): void
    {
        $entity = $beforeEntityPersistedEvent->getEntityInstance();

        if (! ($entity instanceof User)) {
            return;
        }

        $avatar = $this->avatarService->createAvatar($entity->getFullName());
        $entity->setAvatar($avatar);
    }
}
