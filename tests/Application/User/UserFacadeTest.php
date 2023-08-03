<?php

declare(strict_types = 1);

namespace App\Tests\Application\User;

use App\Application\User\Business\UserFacade;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\StoreRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Infrastructure\Persistence\DataFixtures\UserFixtures;
use App\Infrastructure\Test\AbstractWebTestCase;
use App\Infrastructure\Test\Context\Model\StoreContext;
use App\Infrastructure\Test\Context\Model\UserContext;
use App\Shared\Transfer\UserCreate;
use App\Shared\Transfer\UserDelete;
use App\Shared\Transfer\UserUpdate;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFacadeTest extends AbstractWebTestCase
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
        $store = StoreContext::create()();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($store);
        $entityManager->flush();

        $transfer = UserCreate::fromArray([
            'email' => $this->faker->email(),
            'roles' => ['ROLE_ADMIN', 'ROLE_MANAGER'],
            'stores' => [
                (string) $store->id(),
            ],
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
        $this->assertCount(3, $user->roles());
        $this->assertCount(3, $dbUser->roles());
        $this->assertInstanceOf(DateTimeImmutable::class, $user->createdAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $user->updatedAt());
        $this->assertCount(1, $dbUser->stores());
        $this->assertCount(1, $user->stores());
    }

    public function testShouldSuccessfullyDeleteUser(): void
    {
        $user = UserContext::create()();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($user);
        $entityManager->flush();

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        $facade->delete(UserDelete::fromArray(['id' => (string) $user->id()]));

        $this->assertCount(0, iterator_to_array($repository->find()));
    }

    public function testShouldSuccessfullyDeleteUserAndRelations(): void
    {
        $store = StoreContext::create()();
        $user = UserContext::create()();

        $user->addStore($store);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($user);
        $entityManager->flush();

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        /** @var StoreRepositoryInterface $storeRepository */
        $storeRepository = $this->getContainer()->get(StoreRepositoryInterface::class);

        $facade->delete(UserDelete::fromArray(['id' => (string) $user->id()]));

        $this->assertCount(0, iterator_to_array($repository->find()));
        $this->assertCount(0, iterator_to_array($store->users()));

        $dbStore = $storeRepository->findOne($store->id());
        $this->assertCount(0, iterator_to_array($dbStore->users()));
    }

    public function testShouldSuccessfullyHandleAttemptToRemoveNotExisingUser(): void
    {
        $this->expectException(NotFoundException::class);

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        $facade->delete(UserDelete::fromArray(['id' => 'not-existing']));
    }

    public function testShouldSuccessfullyUpdateUser(): void
    {
        $user = UserContext::create()();
        $store = StoreContext::create()();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($store);
        $entityManager->persist($user);
        $entityManager->flush();

        $clonedUser = clone $user;

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        $transfer = UserUpdate::fromArray([
            'email' => 'test@mail.com',
            'roles' => ['ROLE'],
            'stores' => [(string) $store->id()],
            'password' => 'my password',
            'id' => (string) $user->id(),
        ]);

        $updatedUser = $facade->update($transfer);

        $this->assertEquals($clonedUser->id(), $updatedUser->id());
        $this->assertEquals('test@mail.com', (string) $updatedUser->email());
        $this->assertCount(2, $updatedUser->roles());
        $this->assertArrayHasKey('ROLE', array_flip($updatedUser->roles()));
        $this->assertCount(1, $updatedUser->stores());
        $this->assertEquals($store->id(), $updatedUser->stores()->get(0)->id());
    }

    public function testShouldSuccessfullyUpdateUserWithNotExistingStore(): void
    {
        $this->expectException(NotFoundException::class);

        $user = UserContext::create()();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($user);
        $entityManager->flush();

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        $transfer = UserUpdate::fromArray([
            'email' => 'test@mail.com',
            'roles' => ['ROLE'],
            'stores' => ['1111-2222-3333-4444'],
            'password' => 'my password',
            'id' => (string) $user->id(),
        ]);

        $facade->update($transfer);
    }

    public function testUserCouldBeFoundById(): void
    {
        $user = UserContext::create()();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($user);
        $entityManager->flush();

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);
        $dbUser = $facade->findById($user->id());

        $this->assertEquals($user, $dbUser);
    }
}
