<?php

declare(strict_types = 1);

namespace App\Domain\Enum;

use ReflectionEnum;
use ValueError;

enum PaymentStatusEnum: int
{
    case init = 0;
    case token = 1;
    case registered = 2;
    case awaiting = 3; // INTERIM_SUCCESS
    case successfully = 4; // SUCCESS
    case failure = 5; // FAILED
    case timeout = 6; // TRANSACTION_EXPIRED
    case unknown = 1000; // UNKNOWN

    public static function fromName(string $name): self
    {
        $reflection = new ReflectionEnum(self::class);

        return $reflection->hasCase($name)
            ? $reflection->getCase($name)->getValue()
            : throw new ValueError('Key ' . $name . ' is not existing');
    }

    public function status(): string
    {
        return $this->name;
    }

    public function isInitiated(): bool
    {
        return self::init->value == $this->value;
    }

    public function isTokenized(): bool
    {
        return self::token->value == $this->value;
    }

    public function isRegistered(): bool
    {
        return self::registered->value == $this->value;
    }

    public function isAwaiting(): bool
    {
        return self::awaiting->value == $this->value;
    }
}
