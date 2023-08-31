<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Context;

trait ContextTrait
{
    public static function create(): static
    {
        return new static();
    }

    public function __call(string $name, array $arguments): self
    {
        if (str_starts_with($name, 'with')) {
            $property = lcfirst(substr($name, 4));
            $this->$property = $arguments[0];
        }

        return $this;
    }
}
