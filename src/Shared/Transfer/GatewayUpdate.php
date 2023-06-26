<?php

declare(strict_types = 1);

namespace App\Shared\Transfer;

final class GatewayUpdate
{
    use CreateFromTrait;

    public function __construct(
        private readonly string $id,
        private readonly string $title,
        private readonly string $callback,
        private readonly string $host,
        private readonly string $portal,
        private readonly string $currency,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function callback(): string
    {
        return $this->callback;
    }

    public function host(): string
    {
        return $this->host;
    }

    public function portal(): string
    {
        return $this->portal;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function key(): string
    {
        return $this->key;
    }
}
