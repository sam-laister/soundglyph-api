<?php

namespace App\State;

use App\Entity\Media;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\BucketService;

final class FileProcessor implements ProcessorInterface
{
    private EntityManagerInterface $entityManager;
    private BucketService $bucketService;

    public function __construct(EntityManagerInterface $entityManager, BucketService $bucketService)
    {
        $this->entityManager = $entityManager;
        $this->bucketService = $bucketService;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {

        $uploadedFile = $data->getFile();

        $result = $this->bucketService->uploadAction($uploadedFile);

        if ($uploadedFile) {
            $data->setPath($result);
            $data->setFile(null);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}