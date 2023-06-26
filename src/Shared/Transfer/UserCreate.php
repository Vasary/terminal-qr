<?php

declare(strict_types = 1);

namespace App\Shared\Transfer;

final class UserCreate
{
    use CreateFromTrait;

    public function __construct(
        private readonly string $email,
        /** @var string[] */
        private readonly array $roles,
        /** @var string[] */
        private readonly array $stores,
        private readonly string $password,
    ) {
    }

    public function email(): string
    {
        return $this->email;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function stores(): array
    {
        return $this->stores;
    }

    public function password(): string
    {
        return $this->password;
    }
}
