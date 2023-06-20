<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Key;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class KeyType extends StringType
{
    private const NAME = 'key';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Key
    {
        return new Key($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
