<?php

declare(strict_types = 1);

namespace App\Application\User;

use App\Domain\Model\User as DomainUser;
use App\Infrastructure\Security\User\PasswordAuthenticatedUserInterface;
use App\Infrastructure\Security\User\UserInterface;

final readonly class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(private DomainUser $domainUser)
    {
    }

    public function getRoles(): array
    {
        return $this->domainUser->roles();
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->domainUser->id();
    }

    public function getDomainUser(): DomainUser
    {
        return $this->domainUser;
    }

    public function getPassword(): ?string
    {
        return $this->domainUser->password();
    }
}
