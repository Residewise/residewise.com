<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\ConversationRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    /**
     * @var ArrayCollection<int, Message>
     */
    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class)]
    private Collection $messages;

    /**
     * @var ArrayCollection<int, UserInterface>
     */
    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'conversations', cascade: ['persist'])]
    private Collection $people;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->people = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getId(): Uuid
    {
        return $this->id;
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
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        // set the owning side to null (unless already changed)
        if ($this->messages->removeElement($message) && $message->getConversation() === $this) {
            $message->setConversation(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, UserInterface>
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(null|UserInterface $user): self
    {
        if (! $this->people->contains($user)) {
            $this->people[] = $user;
        }

        return $this;
    }

    public function removePerson(UserInterface $user): self
    {
        $this->people->removeElement($user);

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public  function  getUsersFirstNamesAsCommaSeperatedString(): string
    {
        $names = [];
        /** @var User $user */
        foreach ($this->people as $user){
            $names[] = $user->getFirstName();
        }

        return implode(', ', $names);
    }
}
