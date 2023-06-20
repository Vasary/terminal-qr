<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Context;

trait ContextTrait
{
    public static function create(): static
    {
        return new static();
    }
}
