<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

final readonly class PaymentParameter
{
    public function __construct(private string $key, private ?string $value)
    {
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function key(): string
    {
        return $this->key;
    }
}
