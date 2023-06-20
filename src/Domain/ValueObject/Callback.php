<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use Stringable;

final readonly class Callback implements Stringable
{
    public function __construct(private string $callback)
    {
    }

    public function __toString(): string
    {
        return $this->callback;
    }
}
