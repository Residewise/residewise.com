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
use App\Repository\PropertyRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(collectionOperations: [
    'post' => [],
    'get' => []
], denormalizationContext: [
    'groups' => ['property.write'],
], normalizationContext: [
    'groups' => ['property.read'],
])]
#[ApiFilter(SearchFilter::class, properties: [
    'type' => 'end'
])]
#[ApiFilter(RangeFilter::class, properties: ['fee'])]
#[ApiFilter(NumericFilter::class, properties: ['sqm'])]
#[Entity(repositoryClass: PropertyRepository::class)]
class Property implements CreatedAtEntityInterface, PriceableInterface
{
    public const TYPES = [self::TYPE_APARTMENT, self::TYPE_HOUSE, self::TYPE_LAND];

    public const TYPE_APARTMENT = 'TYPE_APARTMENT';

    public const TYPE_HOUSE = 'TYPE_HOUSE';

    public const TYPE_LAND = 'TYPE_LAND';

    public const TERMS = [self::TERM_DAILY, self::TERM_WEEKLY, self::TERM_MONTHLY, self::TERM_ONE_TIME];

    public const TERM_ONE_TIME = 'TERM_ONE_TIME';

    public const TERM_DAILY = 'TERM_DAILY';

    public const TERM_WEEKLY = 'TERM_WEEKLY';

    public const TERM_MONTHLY = 'TERM_MONTHLY';

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[Groups(['property.read', 'user.read'])]
    private Uuid $id;

    #[Groups(['property.write', 'property:read', 'user.read', 'user.write'])]
    #[Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $title;

    #[Column(type: 'text', nullable: true)]
    #[Groups(['property.write', 'property.read', 'user.read', 'user.write'])]
    private ?string $description;

    #[Column(type: 'string', length: 255)]
    #[Groups(['property.write', 'property.read', 'user.read', 'user.write'])]
    #[Assert\Choice(self::TYPES)]
    private string $type;

    #[Column(type: 'integer', precision: 10, scale: 2)]
    #[Groups(['property.write', 'property.read', 'user.read', 'user.write'])]
    #[Assert\PositiveOrZero]
    private int $fee = 0;

    #[Column(type: 'string', length: 3)]
    #[Groups(['property.write', 'property.read', 'user.read', 'user.write'])]
    #[Assert\NotBlank]
    private string $currency = 'CZK';

    #[Column(type: 'string', length: 255)]
    #[Groups(['property.write', 'property.read', 'user.read', 'user.write'])]
    #[Assert\Choice(self::TERMS)]
    private string $term;

    #[Groups(['property.write', 'property:read', 'user.read', 'user.write'])]
    #[Column(type: 'string')]
    private string $address;

    #[Groups(['property.write', 'property:read', 'user.read', 'user.write'])]
    #[Column(type: 'integer')]
    #[Assert\Positive]
    private int $sqm;

    // TODO: set up event subscriber and LocatableEntityInterface to convert address in to coordinates
    #[Groups(['property.write', 'property:read', 'user.read', 'user.write'])]
    #[Column(type: 'float')]
    #[Assert\NotBlank]
    private float $latitude;

    #[Groups(['property.write', 'property:read', 'user.read', 'user.write'])]
    #[Column(type: 'float')]
    #[Assert\NotBlank]
    private float $longitude;

    // TODO figure out how to handle the property owner flow
    #[Groups(['property:read'])]
    #[ManyToMany(targetEntity: User::class, mappedBy: 'properties')]
    private Collection $owners;

    #[Groups(['property:read'])]
    #[ManyToOne(targetEntity: User::class)]
    private ?User $currentOwner;

    // TODO: setup image uploading use base64 / max upload of 2mb and reduce image resolution with image processor.
    #[Groups(['property:read'])]
    #[OneToMany(mappedBy: 'property', targetEntity: Media::class)]
    private Collection $media;

    // TODO: figure out how to handle, expired contracts and contract renewal
    // TODO: (something like if the current date is passed the endedAt date of the contract, ensure all contract parties agree.)
    #[OneToMany(mappedBy: 'property', targetEntity: Contract::class)]
    private Collection $contracts;

    // TODO add event subscriber to reject property without some basic property info
    #[ManyToMany(targetEntity: Feature::class, inversedBy: 'properties')]
    private Collection $features;

    #[Column(type: 'datetime_immutable')]
    #[Groups(['property.write', 'property.read', 'user.read', 'user.write'])]
    #[Assert\NotBlank]
    private DateTimeImmutable $createdAt;

    #[Column(type: 'boolean')]
    #[Groups(['user.read', 'property.read',])]
    private bool $isPublic = false;

    #[Column(type: 'boolean')]
    #[Groups(['user.read', 'property.read',])]
    private bool $isUnderReview = true;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();
        $this->features = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
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

    public function getFee(): int
    {
        return $this->fee;
    }

    public function setFee(int $fee): PriceableInterface
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
        $this->term = $term;

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
     * @return Collection
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addAmenity(Feature $amenity): self
    {
        if (!$this->features->contains($amenity)) {
            $this->features[] = $amenity;
            $amenity->addProperty($this);
        }

        return $this;
    }

    public function removeAmenity(Feature $amenity): self
    {
        // set the owning side to null (unless already changed)
        if ($this->features->removeElement($amenity) && $amenity->getProperties()
                ->contains($this)) {
            $amenity->removeProperty(null);
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
        if (!$this->media->contains($medium)) {
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


    public function getCurrentOwner(): ?User
    {
        return $this->currentOwner;
    }

    public function setCurrentOwner(?User $currentOwner): void
    {
        $this->currentOwner = $currentOwner;
    }

    /**
     * @return Collection
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contract $contract): self
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts[] = $contract;
            $contract->setProperty($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): self
    {
        // set the owning side to null (unless already changed)
        if ($this->contracts->removeElement($contract) && $contract->getProperty() === $this) {
            $contract->setProperty(null);
        }

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getIsUnderReview(): ?bool
    {
        return $this->isUnderReview;
    }

    public function setIsUnderReview(bool $isUnderReview): self
    {
        $this->isUnderReview = $isUnderReview;

        return $this;
    }
}
