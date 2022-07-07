<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\PersonRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap([
    'person' => Person::class,
    'User' => User::class,
    'Agent' => Agent::class,
])]
class Person implements UserInterface, PasswordAuthenticatedUserInterface, Stringable
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

    public function __construct()
    {
        $this->token = Uuid::v4();
        $this->socialAuths = new ArrayCollection();
        $this->assets = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }

    public function getToken(): ?Uuid
    {
        return $this->token;
    }

    public function setToken(?Uuid $token): self
    {
        $this->token = $token;

        return $this;
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

    public function getId(): ?Uuid
    {
        return $this->id;
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
        if ($this->socialAuths->removeElement($socialAuth)) {
            // set the owning side to null (unless already changed)
            if ($socialAuth->getOwner() === $this) {
                $socialAuth->setOwner(null);
            }
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
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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
        return (string)$this->email;
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
}
