<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use Stringable;

final readonly class Key implements Stringable
{
    public function __construct(private string $key)
    {
    }

    public function __toString(): string
    {
        return $this->key;
    }
}
