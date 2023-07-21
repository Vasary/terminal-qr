<?php

declare(strict_types = 1);

namespace App\Application\Contract;

use App\Infrastructure\Security\User\PasswordAuthenticatedUserInterface;

interface UserPasswordHasherInterface
{
    public function hashPassword(PasswordAuthenticatedUserInterface $user, string $plainPassword): string;

    public function isPasswordValid(PasswordAuthenticatedUserInterface $user, string $plainPassword): bool;

    public function needsRehash(PasswordAuthenticatedUserInterface $user): bool;
}
