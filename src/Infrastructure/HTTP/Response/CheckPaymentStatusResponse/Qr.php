<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP\Response\CheckPaymentStatusResponse;

final class Qr
{
    private ?string $payload = null;
    private ?string $imageUrl = null;

    public function payload(): ?string
    {
        return $this->payload;
    }

    public function imageUrl(): ?string
    {
        return $this->imageUrl;
    }
}
