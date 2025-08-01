<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\MediaRepository;
use App\Services\BucketService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetMediaProvider implements ProviderInterface
{

    private MediaRepository $mediaRepository;
    private BucketService $bucketService;

    public function __construct(private Security $security, MediaRepository $mediaRepository, BucketService $bucketService)
    {
        $this->mediaRepository = $mediaRepository;
        $this->bucketService = $bucketService;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $request = $context['request'] ?? null;
        $id = null;

        if ($request) {
            $id = $request->query->get('id');
        }

        if (!$id) {
            throw new \Exception('Media ID is required');
        }

        $media = $this->mediaRepository->find($id);
        if (!$media) {
            throw new \Exception('Media not found');
        }

        return new StreamedResponse(function () use ($media) {
            $stream = $this->bucketService->getByPath($media->getPath());
            $stream->rewind();
            echo $stream->getContents();
        });
    }
}
