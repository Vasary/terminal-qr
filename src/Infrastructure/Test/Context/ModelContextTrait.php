<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Context;

use ReflectionClass;

trait ModelContextTrait
{
    private function setProperty(object $model, string $property, mixed $value): self
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $propertyId = (new ReflectionClass($model))->getProperty($property);
        $propertyId->setAccessible(true);
        $propertyId->setValue($model, $value);

        return $this;
    }

    private function getInstance(string $modelClassPath): object
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        return (new ReflectionClass($modelClassPath))->newInstanceWithoutConstructor();
    }
}
