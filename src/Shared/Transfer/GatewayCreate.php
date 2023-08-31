<?php

declare(strict_types = 1);

namespace App\Shared\Transfer;

final class GatewayCreate
{
    use CreateFromTrait;

    public function __construct(private readonly string $title, private readonly string $callback,)
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
}
