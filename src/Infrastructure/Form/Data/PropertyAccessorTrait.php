<?php

declare(strict_types = 1);

namespace App\Infrastructure\Form\Data;

use LogicException;

trait PropertyAccessorTrait
{
    private function createErrorMessage(string $property): string
    {
        return sprintf('Property %s is not existing in the %s', lcfirst($property), static::class);
    }

    public function __get(string $property): mixed {
        if (!property_exists($this, $property)) {
            throw new LogicException($this->createErrorMessage($property));
        }

        return $this->$property;
    }

    public function __set(string $property, mixed $value): void {

        if (!property_exists($this, $property)) {
            throw new LogicException($this->createErrorMessage($property));
        }

        $this->$property = $value;
    }

    public function __call(string $name, mixed $arguments): mixed
    {
        if (method_exists($this, $name)) {
            return $this->$name();
        }


        if (str_starts_with($name, 'with')) {
            $this->__set(lcfirst(substr($name, 4)), $arguments[0]);

            return $this;
        }

        return $this->__get(lcfirst($name));
    }
}
