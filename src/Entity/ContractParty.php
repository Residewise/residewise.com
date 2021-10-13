<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContractPartyRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: ContractPartyRepository::class)]
class ContractParty
{
    /**
     * @var int
     */
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]private readonly int $id;
    /**
     * @var User|null
     */
    #[ManyToOne(targetEntity: User::class)]
    private ?User $user;
    /**
     * @var DateTimeImmutable
     */
    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $signedAt;
    public function getId(): int
    {
        return $this->id;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }
    /**
     * @param User|null $user
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function getSignedAt(): \DateTimeImmutable
    {
        return $this->signedAt;
    }
    public function setSignedAt(\DateTimeImmutable $signedAt): self
    {
        $this->signedAt = $signedAt;

        return $this;
    }
}
