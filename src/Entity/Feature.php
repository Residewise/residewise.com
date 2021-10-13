<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FeatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;

#[Entity(repositoryClass: FeatureRepository::class)]
class Feature
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]private readonly int $id;
    #[Column(type: 'string', length: 255)]
    private string $title;
    #[ManyToMany(targetEntity: Property::class, mappedBy: 'amenities')]
    private Collection $properties;
    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }
    public function getId(): int
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
    /**
     * @return Collection|Property[]
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }
    public function addProperty(Property $property): self
    {
        if (! $this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->addAmenity($this);
        }

        return $this;
    }
    public function removeProperty(Property $property): self
    {
        if ($this->properties->removeElement($property)) {
            $property->removeAmenity($this);
        }

        return $this;
    }
}
