<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use Stringable;

final readonly class Description implements Stringable
{
    public function __construct(private readonly string $description)
    {
    }

    public function __toString(): string
    {
        return $this->description;
    }
}
