<?php

namespace App\Repository;

use App\Entity\Media;
use App\Entity\User;
use App\Services\BucketService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @extends ServiceEntityRepository<Media>
 */
class MediaRepository extends ServiceEntityRepository
{

    private BucketService $bucketService;

    public function __construct(ManagerRegistry $registry, BucketService $bucketService)
    {
        parent::__construct($registry, Media::class);
        $this->bucketService = $bucketService;
    }

    public function uploadAndCreate(UploadedFile $file, User $owner, bool $flush = true): Media
    {
        // Upload to bucket and get path
        $path = $this->bucketService->uploadAction($file);

        $media = new Media();
        $media->setPath($path);
        $media->setOwner($owner);

        $this->getEntityManager()->persist($media);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $media;
    }
}
