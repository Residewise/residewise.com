<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Contract\PriceableEntityInterface;
use App\Entity\Contract\UserOwnedEntityInterface;
use App\Repository\AssetRepository;
use Carbon\Carbon;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AssetRepository::class)]
class Asset implements Stringable, PriceableEntityInterface, UserOwnedEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    #[Groups(['asset_map'])]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['asset_map'])]
    private string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['asset_map'])]
    private int $sqm;

    #[ORM\Column(type: 'float')]
    #[Groups(['asset_map'])]
    private float $longitude = 0.00;

    #[ORM\Column(type: 'float')]
    #[Groups(['asset_map'])]
    private float $latitude = 0.00;

    #[ORM\Column(type: 'string', length: 10)]
    #[Groups(['asset_map'])]
    private string $type;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['asset_map'])]
    private string $term;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'assets')]
    private null|UserInterface $owner = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['asset_map'])]
    private DateTimeImmutable $createdAt;

    /**
     * @var ArrayCollection<int,Image>
     */
    #[ORM\OneToMany(mappedBy: 'asset', targetEntity: Image::class, cascade: ['persist'])]
    private Collection $images;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['asset_map'])]
    private string $address;

    #[ORM\Column(type: 'integer')]
    #[Groups(['asset_map'])]
    private int $floor;

    /**
     * @var ArrayCollection<int,Review>
     */
    #[ORM\OneToMany(mappedBy: 'asset', targetEntity: Review::class)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'asset', targetEntity: Reaction::class)]
    private Collection $reactions;

    #[ORM\OneToMany(mappedBy: 'asset', targetEntity: AssetView::class)]
    private Collection $views;

    #[ORM\Column(type: 'decimal', precision: 50, scale: 2, nullable: true)]
    private ?string $agencyFee;

    #[ORM\OneToMany(mappedBy: 'asset', targetEntity: Bookmark::class)]
    private Collection $bookmarks;

    #[ORM\OneToMany(mappedBy: 'asset', targetEntity: Tender::class)]
    private Collection $tenders;

    #[ORM\OneToOne(targetEntity: Tender::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Tender $tender = null;

    #[ORM\Column(type: 'string', length: 3, nullable: true)]
    private string $currency = 'EUR';

    #[ORM\ManyToMany(targetEntity: Amenity::class, inversedBy: 'assets')]
    private Collection $amenities;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private string $price;

    public function __construct() {
        $this->createdAt = new DateTimeImmutable();
        $this->images = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->views = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
        $this->tenders = new ArrayCollection();
        $this->amenities = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): Uuid
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

    public function getSqm(): int
    {
        return $this->sqm;
    }

    public function setSqm(int $sqm): self
    {
        $this->sqm = $sqm;

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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getOwner(): null|UserInterface
    {
        return $this->owner;
    }

    public function setOwner(null|UserInterface $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return ArrayCollection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (! $this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAsset($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        // set the owning side to null (unless already changed)
        if ($this->images->removeElement($image) && $image->getAsset() === $this) {
            $image->setAsset(null);
        }

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * @return ArrayCollection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (! $this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setAsset($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        // set the owning side to null (unless already changed)
        if ($this->reviews->removeElement($review) && $review->getAsset() === $this) {
            $review->setAsset(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Reaction>
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    public function addReaction(Reaction $reaction): self
    {
        if (! $this->reactions->contains($reaction)) {
            $this->reactions[] = $reaction;
            $reaction->setAsset($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction): self
    {
        if ($this->reactions->removeElement($reaction)) {
            // set the owning side to null (unless already changed)
            if ($reaction->getAsset() === $this) {
                $reaction->setAsset(null);
            }
        }

        return $this;
    }

    public function getLikes(): int
    {
        $likes = 0;
        /**
         * @var Reaction $reaction
         */
        foreach ($this->reactions as $reaction) {
            if ($reaction->isLike()) {
                $likes++;
            }
        }

        return $likes;
    }

    public function getDislikes(): int
    {
        $dislikes = 0;
        /**
         * @var Reaction $reaction
         */
        foreach ($this->reactions as $reaction) {
            if ($reaction->isDislike()) {
                $dislikes++;
            }
        }

        return $dislikes;
    }

    public function isNew(): bool
    {
        $week = Carbon::parse($this->createdAt)->addWeek();

        return Carbon::now()->isBefore($week);
    }

    public function isLikedByUser(UserInterface $user): bool
    {
        /** @var Reaction $reaction */
        foreach ($this->reactions as $reaction) {
            if ($reaction->getType() === 'like' && $reaction->getOwner() === $user) {
                return true;
            }
        }

        return false;
    }

    public function isDislikedByUser(UserInterface $user): bool
    {
        /** @var Reaction $reaction */
        foreach ($this->reactions as $reaction) {
            if ($reaction->getType() === 'dislike' && $reaction->getOwner() === $user) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection<int, AssetView>
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(AssetView $view): self
    {
        if (! $this->views->contains($view)) {
            $this->views[] = $view;
            $view->setAsset($this);
        }

        return $this;
    }

    public function removeView(AssetView $view): self
    {
        if ($this->views->removeElement($view)) {
            // set the owning side to null (unless already changed)
            if ($view->getAsset() === $this) {
                $view->setAsset(null);
            }
        }

        return $this;
    }

    public function getPageViews(): int
    {
        $views = 0;
        /** @var AssetView $view */
        foreach ($this->views as $view) {
            if (! $view->hasUser()) {
                $views++;
            }
        }

        return $views;
    }

    /**
     * @return Collection<int, Bookmark>
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (! $this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setAsset($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        if ($this->bookmarks->removeElement($bookmark)) {
            // set the owning side to null (unless already changed)
            if ($bookmark->getAsset() === $this) {
                $bookmark->setAsset(null);
            }
        }

        return $this;
    }

    public function isBookmarkedByUser(UserInterface $user): bool
    {
        /** @var Bookmark $bookmark */
        foreach ($this->getBookmarks() as $bookmark) {
            if ($bookmark->getOwner() === $user ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection<int, Tender>
     */
    public function getTenders(): Collection
    {
        return $this->tenders;
    }

    public function addTender(Tender $tender): self
    {
        if (! $this->tenders->contains($tender)) {
            $this->tenders[] = $tender;
            $tender->setAsset($this);
        }

        return $this;
    }

    public function removeTender(Tender $tender): self
    {
        if ($this->tenders->removeElement($tender)) {
            // set the owning side to null (unless already changed)
            if ($tender->getAsset() === $this) {
                $tender->setAsset(null);
            }
        }

        return $this;
    }

    public function getTender(): ?Tender
    {
        return $this->tender;
    }

    public function setTender(?Tender $tender): self
    {
        $this->tender = $tender;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Collection<int, Amenity>
     */
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenity(Amenity $amenity): self
    {
        if (! $this->amenities->contains($amenity)) {
            $this->amenities[] = $amenity;
        }

        return $this;
    }

    public function removeAmenity(Amenity $amenity): self
    {
        $this->amenities->removeElement($amenity);

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAgencyFee(): ?string
    {
        return $this->agencyFee;
    }

    public function setAgencyFee(string $agencyFee): self
    {
        $this->agencyFee = $agencyFee;

        return $this;
    }

    public function isAgent(): bool
    {
        return $this->getOwner() instanceof Agent;
    }
}
