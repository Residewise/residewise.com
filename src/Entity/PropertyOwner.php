<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PropertyOwnerRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertyOwnerRepository::class)
 */
#[ApiResource(
    collectionOperations: [],
    itemOperations: []
)]
class PropertyOwner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $purchasedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private ?User $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Property::class, inversedBy="propertyOwners")
     */
    private ?Property $property;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPurchasedAt(): \DateTimeImmutable
    {
        return $this->purchasedAt;
    }

    public function setPurchasedAt(\DateTimeImmutable $purchasedAt): self
    {
        $this->purchasedAt = $purchasedAt;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $user): static
    {
        $this->owner = $user;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
