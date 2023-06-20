<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use DateTimeImmutable;

class User
{
    private Id $id;
    private DateTimeImmutable $updatedAt;
    private readonly DateTimeImmutable $createdAt;

    private string $password;

    public function __construct(
        private readonly Email $email,
        private array $roles = []
    )
    {
        $this->id = Id::create();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->password = '';
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function withPassword(string $password): void
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): void
    {
        $this->roles[] = $role;
        $this->updatedAt = new DateTimeImmutable();
    }
}
