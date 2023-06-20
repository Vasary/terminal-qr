<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Context;

interface ModelContextInterface
{
    public static function create(): static;

    public static function clean(): void;

    public function __invoke(bool $singleton = true): object;
}
