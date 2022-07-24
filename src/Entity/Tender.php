<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\TenderRepository;
use Carbon\Carbon;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TenderRepository::class)]
class Tender
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Asset::class, inversedBy: 'tenders')]
    private ?Asset $asset = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\OneToOne(targetEntity: Bid::class, cascade: ['persist', 'remove'])]
    private ?Bid $bid = null;

    #[ORM\Column(type: 'integer')]
    private int $durationInDays = 30;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $startAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $endAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getAsset(): ?Asset
    {
        return $this->asset;
    }

    public function setAsset(?Asset $asset): self
    {
        $this->asset = $asset;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBid(): ?Bid
    {
        return $this->bid;
    }

    public function setBid(?Bid $bid): self
    {
        $this->bid = $bid;

        return $this;
    }

    public function getDurationInDays(): ?int
    {
        return $this->durationInDays;
    }

    public function setDurationInDays(int $durationInDays): self
    {
        $this->durationInDays = $durationInDays;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getTimeUntilEnd(): float|int
    {
        $end = Carbon::parse($this->endAt);

        return $end->diffInMilliseconds();
    }

    public function getTimeUntilEndInInterval(): DateInterval
    {
        $end = Carbon::parse($this->endAt);

        return $end->diffAsCarbonInterval()
            ->toDateInterval();
    }

    public function getIsActive(): bool
    {
        $start = Carbon::parse($this->startAt);
        $end = Carbon::parse($this->endAt);

        return Carbon::now()->isBetween($start, $end);
    }

    public function getIsStartPendding(): bool
    {
        $start = Carbon::parse($this->startAt);

        return Carbon::now()->isBefore($start);
    }

    public function getIsComplete(): bool
    {
        $end = Carbon::parse($this->endAt);

        return Carbon::now()->isAfter($end);
    }
}
