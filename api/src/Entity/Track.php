<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\GetCollection;
use App\State\TrackProcessor;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;


#[ORM\Entity(repositoryClass: TrackRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['track:read']],
    denormalizationContext: ['groups' => ['track:write']],
    operations: [
        new GetCollection(),
        new Post(
            outputFormats: ['jsonld' => ['application/ld+json']],
            inputFormats: ['multipart' => ['multipart/form-data']],
            processor: TrackProcessor::class
        ),
        new Get(
            processor: TrackProcessor::class
        ),
        new Patch(
            processor: TrackProcessor::class
        ),
        new Put(
            processor: TrackProcessor::class
        )
    ]
)]
class Track
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups(['track:read'])]
    private ?Uuid $id = null;

    /**
     * @var Collection<int, Album>
     */
    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'tracks')]
    #[Groups(['track:complex:read'])]
    private Collection $albums;

    #[ORM\Column(length: 255)]
    #[Groups(['track:read', 'track:write'])]
    private ?string $title = null;

    #[ApiProperty(openapiContext: ['nullable' => true, 'type' => 'string', 'format' => 'binary'])]
    #[Groups(['track:write'])]
    private ?File $audioFile = null;

    #[ApiProperty(openapiContext: ['nullable' => true, 'type' => 'string', 'format' => 'binary'])]
    #[Groups(['track:write'])]
    private ?File $artworkFile = null;

    #[ORM\ManyToOne(inversedBy: 'tracks')]
    #[Groups(['track:read'])]
    private ?Media $artwork = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['track:read'])]
    private ?Media $audio = null;

    #[ORM\Column]
    #[Groups(['track:read', 'track:write'])]
    private ?int $duration = null;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): static
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
            $album->addTrack($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            $album->removeTrack($this);
        }

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

    public function getAudioFile(): ?File
    {
        return $this->audioFile;
    }

    public function setAudioFile(?File $audioFile): static
    {
        $this->audioFile = $audioFile;

        return $this;
    }

    public function getArtworkFile(): ?File
    {
        return $this->artworkFile;
    }

    public function setArtworkFile(?File $artworkFile): static
    {
        $this->artworkFile = $artworkFile;

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

    public function getAudio(): ?Media
    {
        return $this->audio;
    }

    public function setAudio(Media $audio): static
    {
        $this->audio = $audio;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }
}
