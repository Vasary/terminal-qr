<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer\Normalizer;

use App\Domain\ValueObject\Credentials\Credential;
use App\Domain\ValueObject\Credentials\SPB;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CredentialNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param SPB $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'title' => (string) $object->title(),
            'callback' => (string) $object->callback(),
            'host' => (string) $object->host(),
            'portal' => (string) $object->portal(),
            'currency' => (string) $object->currency(),
            'key' => (string) $object->key(),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Credential;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [SPB::class => true];
    }
}
