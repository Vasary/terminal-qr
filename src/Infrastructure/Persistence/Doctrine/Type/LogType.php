<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Log;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use InvalidArgumentException;
use JsonException;

final class LogType extends JsonType
{
    private const NAME = 'log';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param Log[] $value
     * @throws ConversionException | JsonException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!is_array($value)) {
            throw ConversionException::conversionFailedSerialization($value, 'Logs[]', new InvalidArgumentException());
        }

        $result = array_map(
            fn(Log $log) => [$log->time()->getTimestamp() => $log->value()],
            $value
        );

        return json_encode($result, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): array
    {
        return array_map(
            function (array $log) {
                $dateTimeImmutable = new DateTimeImmutable();
                $createdAt = $dateTimeImmutable->setTimestamp(key($log));

                return new Log(current($log), $createdAt);
            },
            json_decode($value, true)
        );
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
