<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\InsightRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: InsightRepository::class)]
class Insight
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $action;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'insights')]
    private null|User|UserInterface $owner = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $path;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOwner(): null|User|UserInterface
    {
        return $this->owner;
    }

    public function setOwner(null|User|UserInterface $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
