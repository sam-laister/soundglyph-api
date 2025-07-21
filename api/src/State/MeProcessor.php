<?php
namespace App\State;

use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

final class MeProcessor implements ProcessorInterface
{

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        return new JsonResponse($data);
    }
}