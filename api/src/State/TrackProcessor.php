<?php

declare(strict_types=1);


namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Track;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class TrackProcessor implements ProcessorInterface
{

    private MediaRepository $mediaRepository;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(MediaRepository $mediaRepository, EntityManagerInterface $entityManager, Security $security)
    {
        $this->mediaRepository = $mediaRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {

        if (!$data instanceof Track) {
            throw new \InvalidArgumentException('Data must be an instance of Track');
        }

        $owner = $this->security->getUser();
        $flush = false;


        // Handle artwork upload
        if ($data->getArtworkFile() !== null) {
            $result = $this->mediaRepository->uploadAndCreate($data->getArtworkFile(), $owner, $flush);
            $data->setArtwork($result);
            $data->setArtworkFile(null);
        }

        // Handle audio upload
        if ($data->getAudioFile() !== null) {
            $result = $this->mediaRepository->uploadAndCreate($data->getAudioFile(), $owner, $flush);
            $data->setAudio($result);
            $data->setAudioFile(null);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
