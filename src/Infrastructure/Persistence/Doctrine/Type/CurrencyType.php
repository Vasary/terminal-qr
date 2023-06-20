<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\Currency;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class CurrencyType extends StringType
{
    private const NAME = 'currency';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Currency
    {
        return new Currency($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
