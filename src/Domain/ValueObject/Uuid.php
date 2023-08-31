<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

final readonly class Uuid
{
    public function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
