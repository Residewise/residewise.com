<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\Contract\CreatedAtEntityInterface;
use App\Entity\Contract\PriceableInterface;
use App\Entity\Contract\PropertyOwnerEntityInterface;
use App\Repository\PropertyRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PropertyRepository::class)
 */
 #[ApiResource(
     collectionOperations:['get', 'post'],
     itemOperations:['get', 'patch'],
     normalizationContext: [
         'groups' => ['property:read']
     ],
     denormalizationContext: [
        'groups' => ['property:write']
    ],
 )]
#[ApiFilter(SearchFilter::class, properties: [
    'type' => 'end',
    'contract' => 'end',
])]
#[ApiFilter(RangeFilter::class, properties: ['fee'])]
#[ApiFilter(NumericFilter::class, properties: ['sqm'])]
class Property implements CreatedAtEntityInterface, PriceableInterface, PropertyOwnerEntityInterface
{
    public const TYPES = [self::TYPE_APARTMENT, self::TYPE_HOUSE, self::TYPE_LAND];

    public const TYPE_APARTMENT = 'TYPE_APARTMENT';

    public const TYPE_HOUSE = 'TYPE_HOUSE';

    public const TYPE_LAND = 'TYPE_LAND';

    public const CONTRACTS = [self::CONTRACT_SELL, self::CONTRACT_RENT];

    public const CONTRACT_RENT = 'CONTRACT_RENT';

    public const CONTRACT_SELL = 'CONTRACT_SELL';

    public const CONTRACT_SHARE = 'CONTRACT_SHARE';

    public const TERMS = [self::TERM_DAILY, self::TERM_WEEKLY, self::TERM_MONTHLY, self::TERM_ONE_TIME];

    public const TERM_ONE_TIME = 'TERM_ONE_TIME';

    public const TERM_DAILY = 'TERM_DAILY';

    public const TERM_WEEKLY = 'TERM_WEEKLY';

    public const TERM_MONTHLY = 'TERM_MONTHLY';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['property:read'])]
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Groups(['property:read'])]
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['property:read'])]
    private string $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['property:read'])]
    private string $contract;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    #[Groups(['property:read'])]
    private ?string $fee;

    /**
     * @ORM\Column(type="string", length=3)
     */
    #[Groups(['property:read'])]
    private string $currency;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['property:read'])]
    private string $term;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    #[Groups(['property:read'])]
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['property:read'])]
    private string $pluscode;

    /**
     * @ORM\Column(type="float")
     */
    #[Groups(['property:read'])]
    private float $longitude;

    /**
     * @ORM\Column(type="float")
     */
    #[Groups(['property:read'])]
    private float $latitude;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['property:read'])]
    private int $sqm;

    /**
     * @ORM\OneToMany(targetEntity=PropertyOwner::class, mappedBy="property")
     */
    #[Groups(['property:read'])]
    private Collection $propertyOwners;

    /**
     * @ORM\OneToMany(targetEntity=Amenity::class, mappedBy="property")
     */
    #[Groups(['property:read'])]
    private Collection $amenities;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="property")
     */
    #[Groups(['property:read'])]
    private Collection $media;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->propertyOwners = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->media = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getContract(): string
    {
        return $this->contract;
    }

    public function setContract(string $contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getFee(): ?string
    {
        return $this->fee;
    }

    public function setFee(string $fee): PriceableInterface
    {
        $this->fee = $fee;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getTerm(): string
    {
        return $this->term;
    }

    public function setTerm(string $term): self
    {
        $this->term = $this->contract !== self::CONTRACT_RENT ? self::TERM_ONE_TIME : $term;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): CreatedAtEntityInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPluscode(): string
    {
        return $this->pluscode;
    }

    public function setPluscode(string $pluscode): self
    {
        $this->pluscode = $pluscode;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getSqm(): int
    {
        return $this->sqm;
    }

    public function setSqm(int $sqm): self
    {
        $this->sqm = $sqm;

        return $this;
    }

    /**
     * @return Collection|PropertyOwner[]
     */
    public function getPropertyOwners(): Collection
    {
        return $this->propertyOwners;
    }

    public function addPropertyOwner(PropertyOwner $propertyOwner): self
    {
        if (! $this->propertyOwners->contains($propertyOwner)) {
            $this->propertyOwners[] = $propertyOwner;
            $propertyOwner->setProperty($this);
        }

        return $this;
    }

    public function removePropertyOwner(PropertyOwner $propertyOwner): self
    {
        // set the owning side to null (unless already changed)
        if ($this->propertyOwners->removeElement($propertyOwner) && $propertyOwner->getProperty() === $this) {
            $propertyOwner->setProperty(null);
        }

        return $this;
    }

    /**
     * @return Collection|Amenity[]
     */
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenity(Amenity $amenity): self
    {
        if (! $this->amenities->contains($amenity)) {
            $this->amenities[] = $amenity;
            $amenity->setProperty($this);
        }

        return $this;
    }

    public function removeAmenity(Amenity $amenity): self
    {
        // set the owning side to null (unless already changed)
        if ($this->amenities->removeElement($amenity) && $amenity->getProperty() === $this) {
            $amenity->setProperty(null);
        }

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (! $this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setProperty($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        // set the owning side to null (unless already changed)
        if ($this->media->removeElement($medium) && $medium->getProperty() === $this) {
            $medium->setProperty(null);
        }

        return $this;
    }
}
