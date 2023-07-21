<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\Token;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class TokenType extends StringType
{
    private const NAME = 'token';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Token
    {
        return new Token($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
