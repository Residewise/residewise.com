<?php

declare(strict_types=1);

namespace App\Event\Subscriber\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminPasswordHasherSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * @return array<class-string<BeforeEntityPersistedEvent>, string[]>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['hashPassword'],
        ];
    }

    public function hashPassword(BeforeEntityPersistedEvent $beforeEntityPersistedEvent): void
    {
        $entity = $beforeEntityPersistedEvent->getEntityInstance();

        if (! $entity instanceof User) {
            return;
        }

        $entity->setPassword($this->passwordHasher->hashPassword($entity, $entity->getPassword()));
    }
}
