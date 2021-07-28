<?php

declare(strict_types=1);

namespace App\DataPersister\Property;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Property;
use App\Entity\PropertyOwner;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

final class PropertyPostDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {
    }

    /**
     * @param array<mixed> $context
     */
    public function supports(mixed $data, array $context = []): bool
    {
        return $data instanceof Property;
    }

    /**
     * @* @param Property $data
     * @param array<mixed> $context
     */
    public function persist(mixed $data, array $context = [])
    {
        if (strtoupper($context['collection_operation_name']) === Request::METHOD_POST) {
            /** @var User $user */
            $user = $this->security->getUser();

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

    /**
     * @param User         $data
     * @param array<mixed> $context
     */
    public function remove($data, array $context = []): void
    {
        // call your persistence layer to delete $data
    }
}
