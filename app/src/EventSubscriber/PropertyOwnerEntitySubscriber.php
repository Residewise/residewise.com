<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Contract\OwnerEntityInterface;
use App\Entity\Contract\PropertyEntityInterface;
use App\Entity\Contract\PropertyOwnerEntityInterface;
use App\Entity\Property;
use App\Entity\PropertyOwner;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PropertyOwnerEntitySubscriber implements EventSubscriberInterface
{
  public function __construct(
    private TokenStorageInterface $tokenStorage
  ) {
  }

  /**
   * @return array<string, array<int|string>>
   */
  public static function getSubscribedEvents()
  {
    return [
      KernelEvents::VIEW => ['setPropertyOwner', EventPriorities::PRE_WRITE],
    ];
  }

  public function setPropertyOwner(ViewEvent $viewEvent)
  {
    /** @var Property $property */
    $entity = $viewEvent->getControllerResult();
    $method = $viewEvent->getRequest()->getMethod();

    if (!$entity instanceof PropertyOwnerEntityInterface || Request::METHOD_POST !== $method) {
      return;
    }

    /** @var UserInterface $user */
    $user = $this->tokenStorage->getToken()->getUser();

    $propertyOwner = new PropertyOwner();
    $propertyOwner->setOwner($user);
    $propertyOwner->setProperty($entity);
    
  }
}
