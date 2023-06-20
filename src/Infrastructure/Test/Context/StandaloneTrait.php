<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Context;

trait StandaloneTrait
{
    protected static array $instances = [];

    public static function clean(): void
    {
        static::$instances = [];
    }

    protected function obtainInstance(object $model): mixed
    {
        $key = $this->getKey();

        if (!isset(static::$instances[$key])) {
            static::$instances[$key] = $model;
        }

        return static::$instances[$key];
    }

    private function getKey(): string
    {
        return md5(static::class.$this->id);
    }
}
