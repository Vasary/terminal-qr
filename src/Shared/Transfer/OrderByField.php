<?php

declare(strict_types = 1);

namespace App\Shared\Transfer;

final class OrderByField
{
    use CreateFromTrait;

    public function __construct(private readonly string $field, private readonly string $direction)
    {
    }

    public function field(): string
    {
        return $this->field;
    }

    public function direction(): string
    {
        return $this->direction;
    }
}
