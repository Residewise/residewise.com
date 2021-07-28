<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MediaRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'post' => [
            'method' => 'post',
            'path' => '/media',
            'controller' => 'UploadImageAction::class',
            'defaults' => [
                '_api_receive' => false,
            ],
        ],
        'get',
    ],
    itemOperations: [
        'get',
    ]
)]
class Media
{
    public ?File $imageFile;

    public \DateTimeImmutable $updatedAt;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $path;

    /**
     * @ORM\Column(type="integer")
     */
    private int $size;

    /**
     * @Vich\UploadableField(mapping="media", fileNameProperty="path", size="size")
     */
    private ?string $file;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Property::class, inversedBy="media")
     */
    private ?Property $property;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): \Symfony\Component\Uid\Uuid
    {
        return $this->id;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }


    /**
     * @param \Symfony\Component\HttpFoundation\File\File|null $file
     */
    public function setFile(\Symfony\Component\HttpFoundation\File\File | \Symfony\Component\HttpFoundation\File\UploadedFile | null $file = null): void
    {
        $this->imageFile = $file;

        if ($file !== null) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }
}
