<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;

#[ApiResource(
    collectionOperations: [],
    itemOperations: []
)]
#[Entity(repositoryClass: PlanRepository::class)]
class Plan
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]private readonly int $id;

    #[OneToMany(mappedBy: 'plan', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    #[Column(type: 'string', length: 255)]
    private string $title;

    #[Column(type: 'integer')]
    private int $fee;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|Subscription[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (! $this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setPlan($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        // set the owning side to null (unless already changed)
        if ($this->subscriptions->removeElement($subscription) && $subscription->getPlan() === $this) {
            $subscription->setPlan(null);
        }

        return $this;
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

    public function getFee(): int
    {
        return $this->fee;
    }

    public function setFee(int $fee): self
    {
        $this->fee = $fee;

        return $this;
    }
}
