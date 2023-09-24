<?php

declare(strict_types = 1);

namespace App\Domain\Enum;

use ReflectionEnum;
use ValueError;

enum PaymentStatusEnum: int
{
    case new = 0;
    case awaiting = 1;
    case successfully = 2;
    case failure = 3;

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
}
