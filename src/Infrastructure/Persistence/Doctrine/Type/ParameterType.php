<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\Log;
use App\Domain\ValueObject\PaymentParameter;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use InvalidArgumentException;
use JsonException;

final class ParameterType extends JsonType
{
    private const NAME = 'parameter';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param PaymentParameter[] $value
     * @throws ConversionException | JsonException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!is_array($value)) {
            throw ConversionException::conversionFailedSerialization(
                $value,
                'Parameter[]',
                new InvalidArgumentException()
            );
        }

        return json_encode(
            array_map(
                fn(PaymentParameter $parameter) => [$parameter->key() => $parameter->value()],
                $value
            ),
            JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): array
    {
        return array_map(
            fn (array $parameter) => new PaymentParameter(key($parameter), current($parameter)),
            json_decode($value, true)
        );
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
