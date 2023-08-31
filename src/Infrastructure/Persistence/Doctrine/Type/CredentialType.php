<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\Credentials\Credential;
use App\Infrastructure\Persistence\Doctrine\Converter\CredentialConverter;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

final class CredentialType extends JsonType
{
    private const NAME = 'credential';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Credential
    {
        return CredentialConverter::toObject($value);
    }

    /**
     * @throws ConversionException
     * @throws ExceptionInterface
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        return CredentialConverter::toString($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
