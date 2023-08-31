<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer\Normalizer;

use App\Domain\ValueObject\Credentials\Stub;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CredentialStubNormalizer implements NormalizerInterface
{
    /**
     * @param Stub $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'login' => $object->login(),
            'password' => $object->password(),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Stub;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Stub::class => true];
    }
}
