<?php

namespace App\State;

use App\Entity\Media;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;


final class FileProcessor implements ProcessorInterface
{
    private S3Client $s3Client;
    private string $bucket;
    private EntityManagerInterface $entityManager;

    public function __construct(S3Client $s3Client, string $bucket, EntityManagerInterface $entityManager)
    {
        $this->s3Client = $s3Client;
        $this->bucket = $bucket;
        $this->entityManager = $entityManager;
    }

    public function uploadAction(UploadedFile $file)
    {
        $result = $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $file->getFilename(),
            'Body'   => $file->getContent(),
        ]);
        return $result;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {

        $uploadedFile = $data->getFile();

        $result = $this->uploadAction($uploadedFile);

        if ($uploadedFile) {
            $data->setPath($result['ObjectURL']);
            $data->setFile(null);
        }

        // Persist and flush the entity so it gets an ID
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}