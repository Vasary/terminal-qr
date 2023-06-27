<?php

declare(strict_types = 1);

namespace App\Shared\Transfer;

final class GatewayDelete
{
    use CreateFromTrait;

    public function __construct(private readonly string $id,)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
