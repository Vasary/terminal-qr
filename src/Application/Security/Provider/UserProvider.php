<?php

namespace App\Application\Security\Provider;

use App\Application\User\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Infrastructure\Security\AbstractUserProvider;
use RuntimeException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserProvider extends AbstractUserProvider
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    public function loadUserByIdentifier(string $identifier): User
    {
        if ($user = $this->repository->findByEmail(new Email($identifier))) {
            return new User($user);
        }

        throw new UserNotFoundException();
    }

    public function refreshUser(UserInterface $user): User
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', $user::class));
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        throw new RuntimeException('Not implemented');
    }
}
