<?php

declare(strict_types = 1);

namespace App\Application\HealthCheck\Business\Checker;

final readonly class Response
{
    public function __construct(private string $name, private bool $result, private string $message,)
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function result(): bool
    {
        return $this->result;
    }

    public function message(): string
    {
        return $this->message;
    }
}
