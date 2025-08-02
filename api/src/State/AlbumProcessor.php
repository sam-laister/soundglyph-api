<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Album;
use App\Entity\Media;
use App\Services\BucketService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AlbumProcessor implements ProcessorInterface
{
    private BucketService $bucketService;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(BucketService $bucketService, EntityManagerInterface $entityManager, Security $security)
    {
        $this->bucketService = $bucketService;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Album) {
            throw new \Exception('Invalid data');
        }

        if ($data->getTitle() === null) {
            throw new \Exception('Title is required');
        }

        $owner = $this->security->getUser();

        if ($owner === null) {
            throw new \Exception('User not found');
        }

        // $error = $context['request']->files->get('file')->getErrorMessage();
        // if ($error) {
        //     throw new \Exception($error);
        // }

        if ($data->getFile() !== null) {
            $result = $this->bucketService->uploadAction($data->getFile());
            $media = new Media();
            $media->setPath($result);
            $media->setOwner($owner);

            $this->entityManager->persist($media);

            $data->setArtwork($media);
            $data->setFile(null);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
