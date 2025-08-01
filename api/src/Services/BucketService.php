<?php

namespace App\Services;

use Aws\S3\S3Client;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BucketService
{
    private S3Client $s3Client;
    private string $bucket;

    public function __construct(S3Client $s3Client, string $bucket)
    {
        $this->s3Client = $s3Client;
        $this->bucket = $bucket;
    }

    public function getByPath(string $path): StreamInterface
    {
        $result = $this->s3Client->getObject([
            'Bucket' => $this->bucket,
            'Key'    => $path,
        ]);

        return $result['Body'];
    }

    public function uploadAction(UploadedFile $file): string
    {

        if (!$file || !$file->isValid() || !is_readable($file->getPathname())) {
            throw new \InvalidArgumentException('Uploaded file is missing or not readable.');
        }

        $maxSize = 5 * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('File is too large. Maximum size is 5MB.');
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'audio/mpeg'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes, true)) {
            throw new \InvalidArgumentException('Invalid file type. Only JPG, PNG, and GIF are allowed.');
        }

        $filename = uniqid() . '-' . basename($file->getClientOriginalName());

        $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $filename,
            'Body'   => file_get_contents($file->getPathname()),
        ]);

        return $filename;
    }

    public function uploadFile(File $file, string $filename): string
    {

        if (!$file || !is_readable($file->getPathname())) {
            throw new \InvalidArgumentException('Uploaded file is missing or not readable.');
        }

        $maxSize = 5 * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('File is too large. Maximum size is 5MB.');
        }

        $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $filename,
            'Body'   => file_get_contents($file->getPathname()),
        ]);

        return $filename;
    }
}
