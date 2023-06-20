<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer;

use App\Infrastructure\Serializer\Normalizer\AttributeNormalizer;
use App\Infrastructure\Serializer\Normalizer\AttributeValueNormalizer;
use App\Infrastructure\Serializer\Normalizer\CategoryNormalizer;
use App\Infrastructure\Serializer\Normalizer\GlossaryNormalizer;
use App\Infrastructure\Serializer\Normalizer\HealthCheckResponseNormalizer;
use App\Infrastructure\Serializer\Normalizer\ProductNormalizer;
use App\Infrastructure\Serializer\Normalizer\UnitNormalizer;
use App\Infrastructure\Serializer\Normalizer\UUIDNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;

final class Serializer implements SerializerInterface
{
    private static ?SerializerInterface $instance = null;
    private SymfonySerializerInterface $serializer;

    public static function create(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->serializer = new SymfonySerializer($this->getNormalizers());
    }

    /**
     * @throws ExceptionInterface
     */
    public function toArray(object $object): array
    {
        return $this->serializer->normalize($object);
    }

    /**
     * @throws ExceptionInterface
     */
    public function toJson(object $object): string
    {
        return json_encode($this->serializer->normalize($object));
    }

    private function getNormalizers(): array
    {
        return [
            new DateTimeNormalizer(),
            new UUIDNormalizer(),
            new HealthCheckResponseNormalizer(),
            new ObjectNormalizer(),
        ];
    }
}
