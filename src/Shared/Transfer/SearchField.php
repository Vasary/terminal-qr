<?php

declare(strict_types = 1);

namespace App\Shared\Transfer;

final class SearchField
{
    use CreateFromTrait;

    public function __construct(private readonly string $name, private readonly string $value)
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
