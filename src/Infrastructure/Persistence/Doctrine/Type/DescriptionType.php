<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\Description;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class DescriptionType extends StringType
{
    private const NAME = 'description';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Description
    {
        return new Description($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
