<?php

declare(strict_types = 1);

namespace App\Shared\Transfer;

final readonly class GatewayCreate
{
    use CreateFromTrait;

    public function __construct(private string $title, private string $callback, private string $currency)
    {
    }

    public function title(): string
    {
        return $this->title;
    }

    public function callback(): string
    {
        return $this->callback;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}
