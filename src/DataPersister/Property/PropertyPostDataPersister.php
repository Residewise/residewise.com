<?php

declare(strict_types=1);

namespace App\DataPersister\Property;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Property;
use App\Entity\PropertyOwner;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class PropertyPostDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private TokenStorageInterface $tokenStorage, private EntityManagerInterface $entityManager)
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Property;
    }

    /**
     * @* @param Property $data
     */
    public function persist($data, array $context = [])
    {
        if (Request::METHOD_POST === strtoupper($context['collection_operation_name'])) {
            /** @var UserInterface $user */
            $user = $this->tokenStorage->getToken()->getUser();

            $propertyOwner = new PropertyOwner();
            $propertyOwner->setOwner($user);
            $propertyOwner->setPurchasedAt(new DateTimeImmutable());
            $propertyOwner->setCreatedAt(new DateTimeImmutable());
            $propertyOwner->setUpdatedAt(new DateTime());
            $propertyOwner->setProperty($data);

            $this->entityManager->persist($data);
            $this->entityManager->persist($propertyOwner);
            $this->entityManager->flush();
        }

        return $data;
    }

    public function remove($data, array $context = []): void
    {
        // call your persistence layer to delete $data
    }
}
