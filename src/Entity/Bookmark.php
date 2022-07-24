<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\BookmarkRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: BookmarkRepository::class)]
class Bookmark
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Asset::class, inversedBy: 'bookmarks')]
    private null|Asset $asset;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bookmarks')]
    private null|User $owner;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getAsset(): null|Asset
    {
        return $this->asset;
    }

    public function setAsset(null|Asset $asset): self
    {
        $this->asset = $asset;

        return $this;
    }

    public function getOwner(): null|User
    {
        return $this->owner;
    }

    public function setOwner(null|User $owner): self
    {
        $this->owner = $owner;

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
}
