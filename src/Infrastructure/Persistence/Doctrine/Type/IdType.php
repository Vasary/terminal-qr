<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\Id;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\AbstractUid;

final class IdType extends Type
{
    private const NAME = 'id';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if ($platform->hasNativeGuidType()) {
            return $platform->getGuidTypeDeclarationSQL($column);
        }

        return $platform->getBinaryTypeDeclarationSQL([
            'length' => '16',
            'fixed' => true,
        ]);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Id
    {
        if (null === $value) {
            return null;
        }

        if (!\is_string($value)) {
            throw ConversionException::conversionFailedInvalidType(
                $value,
                $this->getName(),
                ['null', 'string', AbstractUid::class]
            );
        }

        try {
            return Id::fromString($value);
        } catch (\InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        try {
            return null !== $value
                ? (string) $value
                : null;
        } catch (\InvalidArgumentException) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
