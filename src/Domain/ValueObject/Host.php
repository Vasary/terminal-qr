<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use Stringable;

final readonly class Host implements Stringable
{
    public function __construct(private string $host)
    {
    }

    public function __toString(): string
    {
        return $this->host;
    }
}
