<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use Stringable;

final readonly class Code implements Stringable
{
    public function __construct(private readonly string $code)
    {
    }

    public function code(): string
    {
        return $this->code;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
