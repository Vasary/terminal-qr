<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use Stringable;

final readonly class Token implements Stringable
{
    public function __construct(private string $token)
    {
    }

    public function __toString(): string
    {
        return $this->token;
    }
}
