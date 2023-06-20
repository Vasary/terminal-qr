<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer\Normalizer;

use App\Application\HealthCheck\Business\Checker\Response;
use App\Infrastructure\Map\ParametersList;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class HealthCheckResponseNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        /* @var Response $object */
        return
            [
                'service' => $object->name(),
                'result' => $object->result(),
                'message' => $object->message(),
            ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Response;
    }
}
