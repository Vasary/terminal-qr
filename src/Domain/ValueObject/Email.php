<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use Stringable;

final readonly class Email implements Stringable
{
    public function __construct(private string $email)
    {
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
