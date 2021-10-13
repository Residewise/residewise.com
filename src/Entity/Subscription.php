<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use App\Repository\SubscriptionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]private readonly int $id;
    #[ManyToOne(targetEntity: User::class, inversedBy: 'subscriptions')]
    private ?User $user;
    #[ManyToOne(targetEntity: Plan::class, inversedBy: 'subscriptions')]
    private ?Plan $plan;
    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;
    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $finishedAt;
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
    public function getPlan(): ?Plan
    {
        return $this->plan;
    }
    /**
     * @param Plan|null $plan
     */
    public function setPlan(?Plan $plan): self
    {
        $this->plan = $plan;

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
    public function getFinishedAt(): \DateTimeImmutable
    {
        return $this->finishedAt;
    }
    public function setFinishedAt(\DateTimeImmutable $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }
}
