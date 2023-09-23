<?php

declare(strict_types = 1);

namespace App\Application\Security\Provider;

use App\Application\User\User;
use App\Domain\Exception\UserNotFoundException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Infrastructure\Security\AbstractUserProvider;
use RuntimeException;
use Throwable;

final class UserProvider extends AbstractUserProvider
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function loadUserByIdentifier(string $identifier): User
    {
        try {
            if ($user = $this->repository->findByEmail(new Email($identifier))) {
                return new User($user);
            }
        } catch (Throwable) {}

        throw new UserNotFoundException();
    }

    public function refreshUser(mixed $user): User
    {
        if (!$user instanceof User) {
            throw new RuntimeException(sprintf('Invalid user class "%s".', $user::class));
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function upgradePassword(mixed $user, string $newHashedPassword): void
    {
        throw new RuntimeException('Not implemented');
    }
}
