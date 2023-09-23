<?php

declare(strict_types = 1);

namespace App\Infrastructure\Form\Data;

use LogicException;

trait PropertyAccessorTrait
{
    public function __get(string $property): mixed {
        if (!method_exists($this, $property)) {
            throw new LogicException($property . ' is not existing in ' . static::class);
        }

        return $this->$property();
    }

    public function __set(string $property, mixed $value): void {

        $method = 'with' . ucfirst($property);

        if (!method_exists($this, $method)) {
            throw new LogicException($method . ' is not existing in ' . static::class);
        }

        $this->$method($value);
    }
}

//     public function __call($name, $arguments): self
//    {
//        if (str_starts_with($name, 'with')) {
//            $property = lcfirst(substr($name, 4));
//            $this->$property = $arguments[0];
//        }
//
//        return $this;
//    }
