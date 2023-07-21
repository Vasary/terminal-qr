<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP\Response;

final readonly class TokenResponse
{
    private ?string $token;

    public function token(): ?string
    {
        return $this->token;
    }
}
