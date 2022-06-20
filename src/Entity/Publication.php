<?php

namespace App\Entity;

use Stringable;
use App\Repository\PublicationRepository;
use Carbon\Carbon;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use function dump;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Asset::class, cascade: ['persist'], inversedBy: 'publications')]
    private ?Asset $asset = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isApproved = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $endsAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $startsAt;

    public function __construct()
    {
        $this->startsAt = new DateTimeImmutable();
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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function __toString(): string
    {
        $startsAt = Carbon::parse($this->startsAt);
        $endsAt = Carbon::parse($this->endsAt);
        return $startsAt->toRfc822String() . "-----" . $endsAt->toRfc822String();
    }

    public function getEndsAt(): ?\DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTimeImmutable $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getStartsAt(): ?\DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTimeImmutable $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        $endsAt = Carbon::parse($this->endsAt);
        return Carbon::now()->isBefore($endsAt) && $this->isApproved;
    }

}
