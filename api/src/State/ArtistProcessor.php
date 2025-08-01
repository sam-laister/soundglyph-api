<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
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
        if ($data->getFile() !== null) {
            $result = $this->bucketService->uploadAction($data->getFile());
            $data->setArtworkPath($result);
            $data->setFile(null);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
