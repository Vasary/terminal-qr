<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer\Normalizer;

use App\Domain\ValueObject\Id;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class UUIDNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function normalize(mixed $object, string $format = null, array $context = []): string
    {
        return (string) $object;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Id;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Id::class];
    }
}
