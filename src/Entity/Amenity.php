<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\AmenityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AmenityRepository::class)]
class Amenity implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    /** @var ArrayCollection<int, Asset> */
    #[ORM\ManyToMany(targetEntity: Asset::class, mappedBy: 'amenities')]
    private Collection $assets;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private null|string $icon;

    public function __construct()
    {
        $this->assets = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Asset>
     */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function addAsset(Asset $asset): self
    {
        if (! $this->assets->contains($asset)) {
            $this->assets[] = $asset;
            $asset->addAmenity($this);
        }

        return $this;
    }

    public function removeAsset(Asset $asset): self
    {
        if ($this->assets->removeElement($asset)) {
            $asset->removeAmenity($this);
        }

        return $this;
    }

    public function getIcon(): null|string
    {
        return $this->icon;
    }

    public function setIcon(null|string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }
}
