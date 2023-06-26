<?php

declare(strict_types = 1);

namespace App\Tests\Application\User;

use App\Application\User\Business\UserFacade;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Infrastructure\Persistence\DataFixtures\UserFixtures;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Shared\Transfer\UserCreate;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFacadeTest extends AbstractUnitTestCase
{
    public function testStoreFacadeShouldRetrieveUsers(): void
    {
        $this->loadFixtures(new UserFixtures($this->getContainer()->get(UserPasswordHasherInterface::class)));

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        $this->assertCount(2, iterator_to_array($facade->find()));
    }

    public function testUserFacadeShouldCreateUser(): void
    {
        $transfer = UserCreate::fromArray([
            'email' => $this->faker->email(),
            'roles' => ['USER', 'ADMIN'],
            'stores' => [],
            'password' => 'my password',
        ]);

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        $user = $facade->create($transfer);

        $this->assertEquals($transfer->email(), $user->email());
        $this->assertCount(1, iterator_to_array($repository->find()));

        $dbUser = $repository->findByEmail(new Email($transfer->email()));
        $this->assertCount(2, $user->roles());
        $this->assertCount(2, $dbUser->roles());
        $this->assertInstanceOf(DateTimeImmutable::class, $user->createdAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $user->updatedAt());
    }
}
