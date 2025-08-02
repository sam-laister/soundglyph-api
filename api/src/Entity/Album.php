<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Metadata\ApiProperty;
use App\State\AlbumProcessor;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['album:read', 'media:read', 'artist:read']],
    denormalizationContext: ['groups' => ['album:write']],
    operations: [
        new GetCollection(),
        new Post(
            outputFormats: ['jsonld' => ['application/ld+json']],
            inputFormats: ['multipart' => ['multipart/form-data']],
            processor: AlbumProcessor::class,
        ),
        new Get(
            normalizationContext: ['groups' => ['album:read', 'media:read', 'artist:read', 'track:read']],
        ),
        new Patch(
            processor: AlbumProcessor::class
        ),
        new Put(
            processor: AlbumProcessor::class
        )
    ]
)]
class Album
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups(['album:read'])]
    private ?Uuid $id = null;

    /**
     * @var Collection<int, Track>
     */
    #[ORM\ManyToMany(targetEntity: Track::class, inversedBy: 'albums')]
    #[Groups(['album:read'])]
    private Collection $tracks;

    /**
     * @var Collection<int, Artist>
     */
    #[ORM\ManyToMany(targetEntity: Artist::class, inversedBy: 'albums')]
    #[Groups(['album:read', 'album:write'])]
    private Collection $artist;

    #[ORM\Column(length: 255)]
    #[Groups(['album:read', 'album:write'])]
    private ?string $title = null;

    #[ApiProperty(openapiContext: ['nullable' => true, 'type' => 'string', 'format' => 'binary'])]
    #[Groups(['album:write'])]
    private ?File $file = null;

    #[ORM\ManyToOne(inversedBy: 'albums')]
    #[Groups(['album:read'])]
    private ?Media $artwork = null;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->artist = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): static
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks->add($track);
        }

        return $this;
    }

    public function removeTrack(Track $track): static
    {
        $this->tracks->removeElement($track);

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtist(): Collection
    {
        return $this->artist;
    }

    public function addArtist(Artist $artist): static
    {
        if (!$this->artist->contains($artist)) {
            $this->artist->add($artist);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): static
    {
        $this->artist->removeElement($artist);

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getArtwork(): ?Media
    {
        return $this->artwork;
    }

    public function setArtwork(?Media $artwork): static
    {
        $this->artwork = $artwork;

        return $this;
    }
}
