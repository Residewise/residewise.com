<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ContractRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource]
#[Entity(repositoryClass: ContractRepository::class)]
class Contract
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'uuid', unique: true)]
    #[Groups(['user.read'])]private readonly Uuid $id;

    #[Column(type: 'string', length: 255)]
    #[Groups(['user.read', 'user.write'])]
    private string $title;

    #[Column(type: 'datetime_immutable')]
    #[Groups(['user.read'])]
    private DateTimeImmutable $createdAt;

    #[Column(type: 'datetime_immutable')]
    #[Groups(['user.read'])]private readonly DateTimeImmutable $startedAt;

    #[Column(type: 'datetime_immutable')]
    #[Groups(['user.read'])]private readonly DateTimeImmutable $endedAt;

    #[ManyToOne(targetEntity: Property::class, inversedBy: 'contracts')]
    private ?Property $property;

    #[ManyToMany(targetEntity: User::class, mappedBy: 'contracts')]
    private Collection $users;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();
        $this->users = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    /**
     * @param Property|null $property
     */
    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addContract($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeContract($this);
        }

        return $this;
    }
}
