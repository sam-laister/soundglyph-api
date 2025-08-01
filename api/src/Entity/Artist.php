<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use App\State\ArtistProcessor;
use ApiPlatform\Metadata\ApiProperty;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;


#[ORM\Entity(repositoryClass: ArtistRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['artist:read']],
    denormalizationContext: ['groups' => ['artist:write']],
    operations: [
        new GetCollection(),
        new Post(
            outputFormats: ['jsonld' => ['application/ld+json']],
            inputFormats: ['multipart' => ['multipart/form-data']],
            processor: ArtistProcessor::class,
        ),
        new Get(
            processor: ArtistProcessor::class
        ),
        new Patch(
            processor: ArtistProcessor::class
        ),
        new Put(
            processor: ArtistProcessor::class
        )
    ]
)]
class Artist
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups(['artist:read'])]
    private ?Uuid $id = null;

    /**
     * @var Collection<int, Album>
     */
    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'artist')]
    #[Groups(['artist:complex:read'])]
    private Collection $albums;

    #[ORM\Column(length: 255)]
    #[Groups(['artist:write', 'artist:read'])]
    private ?string $name = null;

    #[ApiProperty(openapiContext: ['nullable' => true, 'type' => 'string', 'format' => 'binary'])]
    #[Groups(['artist:write'])]
    private ?File $file = null;

    #[ORM\ManyToOne(inversedBy: 'artists')]
    #[Groups(['artist:read'])]
    private ?Media $artwork = null;

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
            $album->addArtist($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            $album->removeArtist($this);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
