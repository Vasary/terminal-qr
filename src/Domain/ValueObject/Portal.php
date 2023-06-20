<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use Stringable;

final readonly class Portal implements Stringable
{
    public function __construct(private string $portal)
    {
    }

    public function __toString(): string
    {
        return $this->portal;
    }
}
