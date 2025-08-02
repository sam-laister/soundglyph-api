<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Artist;
use App\Entity\Media;
use App\Services\BucketService;
use Doctrine\ORM\EntityManagerInterface;

class ArtistProcessor implements ProcessorInterface
{

    private BucketService $bucketService;
    private EntityManagerInterface $entityManager;

    public function __construct(BucketService $bucketService, EntityManagerInterface $entityManager)
    {
        $this->bucketService = $bucketService;
        $this->entityManager = $entityManager;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {

        if (!$data instanceof Artist) {
            throw new \Exception('Invalid data');
        }

        if ($data->getFile() !== null) {
            $result = $this->bucketService->uploadAction($data->getFile());

            $media = new Media();
            $media->setPath($result);

            $data->setArtwork($media);
            $data->setFile(null);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
