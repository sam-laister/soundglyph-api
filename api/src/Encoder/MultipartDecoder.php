<?php
// api/src/Encoder/MultipartDecoder.php

namespace App\Encoder;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class MultipartDecoder implements DecoderInterface
{
    public const FORMAT = 'multipart';

    public function __construct(private readonly RequestStack $requestStack) {}

    public function decode(string $data, string $format, array $context = []): ?array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return null;
        }

        return $request->request->all() + $request->files->all();

        // return array_map(static function (string $element) {
        //     // Multipart form values will be encoded in JSON.
        //     try {
        //         $trimmed = trim($element);

        //         if ($trimmed === '' || (!str_starts_with($trimmed, '{') && !str_starts_with($trimmed, '['))) {
        //             return $element;
        //         }

        //         return json_decode($element, true, flags: \JSON_THROW_ON_ERROR);
        //     } catch (\Exception $e) {
        //         dd($e);
        //         return $element;
        //     }
        // }, $request->request->all()) + $request->files->all();
    }

    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}
