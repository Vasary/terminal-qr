<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject\Credentials;

final class Stub extends Credential
{
    public function __construct(private readonly string $login, private readonly string $password,)
    {
    }

    public function login(): string
    {
        return $this->login;
    }

    public function password(): string
    {
        return $this->password;
    }
}
