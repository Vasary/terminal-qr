<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP\Response\CheckPaymentStatusResponse;

final readonly class Src
{
    private ?string $type;

    public function type(): ?string
    {
        return $this->type;
    }
}
