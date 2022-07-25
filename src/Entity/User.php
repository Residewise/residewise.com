<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $lastName;

    /**
     * @var ArrayCollection<int, Asset>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Asset::class)]
    private Collection $assets;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'text')]
    private string $avatar;

    /** @var Collection<int, SocialAuth> */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: SocialAuth::class)]
    private Collection $socialAuths;

    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $token;

    #[ORM\Column(type: 'boolean')]
    private bool $isEnabled = false;

    /**
     * @var ArrayCollection<int, Message>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Message::class)]
    private Collection $messages;

    /**
     * @var ArrayCollection<int, Conversation>
     */
    #[ORM\ManyToMany(targetEntity: Conversation::class, mappedBy: 'users', cascade: ['persist'])]
    private Collection $conversations;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    /**
     * @var ArrayCollection<int, AssetView>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: AssetView::class)]
    private Collection $assetViews;

    /**
     * @var ArrayCollection<int, Insight>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Insight::class)]
    private Collection $insights;

    /**
     * @var ArrayCollection<int, Review>
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Review::class,)]
    private Collection $ownedReviews;

    /**
     * @var ArrayCollection<int, Review>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    private Collection $reviews;

    /**
     * @var ArrayCollection<int, Reaction>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Reaction::class)]
    private Collection $reactions;

    /**
     * @var ArrayCollection<int, Bookmark>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Bookmark::class)]
    private Collection $bookmarks;

    /**
     * @var ArrayCollection<int, Bid>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Bid::class)]
    private Collection $bids;

    public function __construct()
    {
        $this->insights = new ArrayCollection();
        $this->ownedReviews = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
        $this->bids = new ArrayCollection();
        $this->token = Uuid::v4();
        $this->socialAuths = new ArrayCollection();
        $this->assets = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->assetViews = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }

    /**
     * @return ArrayCollection<int, Insight>
     */
    public function getInsights(): Collection
    {
        return $this->insights;
    }

    public function addInsight(Insight $insight): self
    {
        if (! $this->insights->contains($insight)) {
            $this->insights[] = $insight;
            $insight->setOwner($this);
        }

        return $this;
    }

    public function removeInsight(Insight $insight): self
    {
        // set the owning side to null (unless already changed)
        if ($this->insights->removeElement($insight) && $insight->getOwner() === $this) {
            $insight->setOwner(null);
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Review>
     */
    public function getOwnedReviews(): Collection
    {
        return $this->ownedReviews;
    }

    public function addOwnedReview(Review $ownedReview): self
    {
        if (! $this->ownedReviews->contains($ownedReview)) {
            $this->ownedReviews[] = $ownedReview;
            $ownedReview->setAuthor($this);
        }

        return $this;
    }

    public function removeOwnedReview(Review $ownedReview): self
    {
        // set the owning side to null (unless already changed)
        if ($this->ownedReviews->removeElement($ownedReview) && $ownedReview->getAuthor() === $this) {
            $ownedReview->setAuthor(null);
        }

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
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        // set the owning side to null (unless already changed)
        if ($this->reviews->removeElement($review) && $review->getUser() === $this) {
            $review->setUser(null);
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
            $reaction->setOwner($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction): self
    {
        // set the owning side to null (unless already changed)
        if ($this->reactions->removeElement($reaction) && $reaction->getOwner() === $this) {
            $reaction->setOwner(null);
        }

        return $this;
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
            $bookmark->setOwner($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        // set the owning side to null (unless already changed)
        if ($this->bookmarks->removeElement($bookmark) && $bookmark->getOwner() === $this) {
            $bookmark->setOwner(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (! $this->bids->contains($bid)) {
            $this->bids[] = $bid;
            $bid->setOwner($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        // set the owning side to null (unless already changed)
        if ($this->bids->removeElement($bid) && $bid->getOwner() === $this) {
            $bid->setOwner(null);
        }

        return $this;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getToken(): ?Uuid
    {
        return $this->token;
    }

    public function setToken(Uuid $token): void
    {
        $this->token = $token;
    }

    public function isIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return ArrayCollection<int, Asset>
     */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function addAsset(Asset $asset): self
    {
        if (! $this->assets->contains($asset)) {
            $this->assets[] = $asset;
            $asset->setOwner($this);
        }

        return $this;
    }

    public function removeAsset(Asset $asset): self
    {
        // set the owning side to null (unless already changed)
        if ($this->assets->removeElement($asset) && $asset->getOwner() === $this) {
            $asset->setOwner(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, SocialAuth>
     */
    public function getSocialAuths(): Collection
    {
        return $this->socialAuths;
    }

    public function addSocialAuth(SocialAuth $socialAuth): self
    {
        if (! $this->socialAuths->contains($socialAuth)) {
            $this->socialAuths[] = $socialAuth;
            $socialAuth->setOwner($this);
        }

        return $this;
    }

    public function removeSocialAuth(SocialAuth $socialAuth): self
    {
        // set the owning side to null (unless already changed)
        if ($this->socialAuths->removeElement($socialAuth) && $socialAuth->getOwner() === $this) {
            $socialAuth->setOwner(null);
        }

        return $this;
    }

    /**
     * @return mixed[]
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * @param mixed[] $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (! $this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setOwner($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        // set the owning side to null (unless already changed)
        if ($this->messages->removeElement($message) && $message->getOwner() === $this) {
            $message->setOwner(null);
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (! $this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->addUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            $conversation->removeUser($this);
        }

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
     * @return Collection<int, AssetView>
     */
    public function getAssetViews(): Collection
    {
        return $this->assetViews;
    }

    public function addAssetView(AssetView $assetView): self
    {
        if (! $this->assetViews->contains($assetView)) {
            $this->assetViews[] = $assetView;
            $assetView->setOwner($this);
        }

        return $this;
    }

    public function removeAssetView(AssetView $assetView): self
    {
        // set the owning side to null (unless already changed)
        if ($this->assetViews->removeElement($assetView) && $assetView->getOwner() === $this) {
            $assetView->setOwner(null);
        }

        return $this;
    }
}
