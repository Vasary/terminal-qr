<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer;

use App\Infrastructure\Serializer\Denormalizer\CredentialSPBDenormalizer;
use App\Infrastructure\Serializer\Normalizer\CredentialSPBNormalizer;
use App\Infrastructure\Serializer\Normalizer\CredentialStubNormalizer;
use App\Infrastructure\Serializer\Normalizer\HealthCheckResponseNormalizer;
use App\Infrastructure\Serializer\Normalizer\UUIDNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
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
        $this->serializer = new SymfonySerializer($this->getNormalizers(), [new JsonEncoder()]);
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

    public function toType(array $data, string $type): object
    {
        return $this->serializer->denormalize($data, $type);
    }

    public function deserialize(string $data, string $type): object
    {
        return $this->serializer->deserialize($data, $type, JsonEncoder::FORMAT);
    }

    private function getNormalizers(): array
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $discriminator = new ClassDiscriminatorFromClassMetadata($classMetadataFactory);

        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);
        $propertyNormalizer = new PropertyNormalizer(
            null,
            new MetadataAwareNameConverter($classMetadataFactory),
            $extractor,
        );

        return [
            new UUIDNormalizer(),
            new HealthCheckResponseNormalizer(),
            new CredentialSPBNormalizer(),
            new DateTimeNormalizer(),
            new CredentialSPBDenormalizer(),
            new CredentialStubNormalizer(),
            new ObjectNormalizer(
                classMetadataFactory: $classMetadataFactory,
                classDiscriminatorResolver: $discriminator
            ),
            $propertyNormalizer,
        ];
    }
}
