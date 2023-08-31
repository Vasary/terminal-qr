<?php

declare(strict_types = 1);

use App\Application\Security\Provider\UserProvider;
use App\Application\User\User;
use App\Domain\Exception\UserNotFoundException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Security\User\PasswordAuthenticatedUserInterface;
use App\Infrastructure\Security\User\UserInterface;
use App\Infrastructure\Test\Context\Model\UserContext;
use Doctrine\ORM\EntityManagerInterface;

test('should successfully load user by identifier', function () {
    $user = UserContext::create()();

    /** @var EntityManagerInterface $entityManager */
    $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

    $entityManager->persist($user);
    $entityManager->flush();

    /** @var UserRepositoryInterface $repository */
    $repository = $this->getContainer()->get(UserRepositoryInterface::class);

    $provider = new UserProvider($repository);

    $applicationUser = $provider->loadUserByIdentifier((string) $user->email());

    expect($applicationUser)->toBeInstanceOf(User::class);
    expect($applicationUser->getDomainUser())->toEqual($user);
});
test('should fails try to load user by invalid identifier', function () {
    $this->expectException(UserNotFoundException::class);

    /** @var UserRepositoryInterface $repository */
    $repository = $this->getContainer()->get(UserRepositoryInterface::class);

    $provider = new UserProvider($repository);

    $provider->loadUserByIdentifier('not-existing@localhost.com');
});
test('should successfully refresh user', function () {
    /** @var UserRepositoryInterface $repository */
    $repository = $this->getContainer()->get(UserRepositoryInterface::class);

    $provider = new UserProvider($repository);

    $user = UserContext::create()();
    $applicationUser = new User($user);

    $refreshedUser = $provider->refreshUser($applicationUser);
    expect($refreshedUser)->toBe($applicationUser);
});
test('fails on refresh user', function () {
    $this->expectException(RuntimeException::class);

    /** @var UserRepositoryInterface $repository */
    $repository = $this->getContainer()->get(UserRepositoryInterface::class);

    $provider = new UserProvider($repository);

    $applicationUser = Mockery::mock(UserInterface::class);
    $provider->refreshUser($applicationUser);
});
test('should check class support condition', function () {
    /** @var UserRepositoryInterface $repository */
    $repository = $this->getContainer()->get(UserRepositoryInterface::class);

    $provider = new UserProvider($repository);

    $applicationUserInvalid = Mockery::mock(UserInterface::class);

    expect($provider->supportsClass(User::class))->toBeTrue();
    expect($provider->supportsClass($applicationUserInvalid::class))->toBeFalse();
});
test('should fails on unimplemented method', function () {
    $this->expectException(RuntimeException::class);

    /** @var UserRepositoryInterface $repository */
    $repository = $this->getContainer()->get(UserRepositoryInterface::class);

    $provider = new UserProvider($repository);
    $applicationUser = Mockery::mock(PasswordAuthenticatedUserInterface::class);

    $provider->upgradePassword($applicationUser, '');
});
