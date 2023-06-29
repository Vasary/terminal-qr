<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use DateTimeImmutable;

final readonly class Log
{
    public function __construct(private string $value, private DateTimeImmutable $time)
    {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function time(): DateTimeImmutable
    {
        return $this->time;
    }
}
