<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Contract\CreatedAtEntityInterface;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CreatedAtEntitySubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, array<int|string>>
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setCreatedAtDate', EventPriorities::PRE_WRITE],
        ];
    }

    public function setCreatedAtDate(ViewEvent $viewEvent): void
    {
        $entity = $viewEvent->getControllerResult();
        $method = $viewEvent->getRequest()->getMethod();

        if (! $entity instanceof CreatedAtEntityInterface || Request::METHOD_POST !== $method) {
            return;
        }

        $entity->setCreatedAt(new DateTimeImmutable());
    }
}
