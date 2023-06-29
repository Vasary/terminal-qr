<?php

declare(strict_types=1);


namespace App\Tests\Application\Security\Provider;

use App\Application\Security\Provider\UserProvider;
use App\Application\User\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Infrastructure\Test\Context\Model\UserContext;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use RuntimeException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserProviderTest extends AbstractUnitTestCase
{
    public function testShouldSuccessfullyLoadUserByIdentifier(): void
    {
        $user = UserContext::create()();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($user);
        $entityManager->flush();


        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        $provider = new UserProvider($repository);

        $applicationUser = $provider->loadUserByIdentifier((string) $user->email());

        $this->assertInstanceOf(User::class, $applicationUser);
        $this->assertEquals($user, $applicationUser->getDomainUser());
    }

    public function testShouldFailsTryToLoadUserByInvalidIdentifier(): void
    {
        $this->expectException(UserNotFoundException::class);

        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        $provider = new UserProvider($repository);

        $provider->loadUserByIdentifier('not-existing@localhost.com');
    }

    public function testShouldSuccessfullyRefreshUser(): void
    {
        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        $provider = new UserProvider($repository);

        $user = UserContext::create()();
        $applicationUser = new User($user);

        $refreshedUser = $provider->refreshUser($applicationUser);
        $this->assertSame($applicationUser, $refreshedUser);
    }

    public function testFailsOnRefreshUser(): void
    {
        $this->expectException(UnsupportedUserException::class);

        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        $provider = new UserProvider($repository);

        $applicationUser = Mockery::mock(UserInterface::class);
        $provider->refreshUser($applicationUser);
    }

    public function testShouldCheckClassSupportCondition(): void
    {
        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        $provider = new UserProvider($repository);

        $applicationUserInvalid = Mockery::mock(UserInterface::class);

        $this->assertTrue($provider->supportsClass(User::class));
        $this->assertFalse($provider->supportsClass($applicationUserInvalid::class));
    }

    public function testShouldFailsOnUnimplementedMethod(): void
    {
        $this->expectException(RuntimeException::class);

        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        $provider = new UserProvider($repository);
        $applicationUser = Mockery::mock(PasswordAuthenticatedUserInterface::class);

        $provider->upgradePassword($applicationUser, '');
    }
}
