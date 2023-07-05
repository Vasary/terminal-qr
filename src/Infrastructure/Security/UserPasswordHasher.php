<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Application\Contract\UserPasswordHasherInterface;
use App\Infrastructure\Security\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as SymfonyUserPasswordHasherInterface;

final readonly class UserPasswordHasher implements UserPasswordHasherInterface
{
    public function __construct(private SymfonyUserPasswordHasherInterface $hasher)
    {
    }

    public function hashPassword(PasswordAuthenticatedUserInterface $user, string $plainPassword): string
    {
        return $this->hasher->hashPassword($user, $plainPassword);
    }

    public function isPasswordValid(PasswordAuthenticatedUserInterface $user, string $plainPassword): bool
    {
        return $this->isPasswordValid($this, $plainPassword);
    }

    public function needsRehash(PasswordAuthenticatedUserInterface $user): bool
    {
        return $this->hasher->needsRehash($user);
    }
}
