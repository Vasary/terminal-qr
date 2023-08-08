<?php

declare(strict_types = 1);

namespace App\Tests\Application\User;

use App\Application\User\Business\UserFacade;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\StoreRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Infrastructure\Persistence\DataFixtures\UserFixtures;
use App\Infrastructure\Test\Context\Model\StoreContext;
use App\Infrastructure\Test\Context\Model\UserContext;
use App\Shared\Transfer\UserCreate;
use App\Shared\Transfer\UserDelete;
use App\Shared\Transfer\UserUpdate;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

describe('User facade', function () {
    it('should return users', function () {
        $this->loadFixtures(new UserFixtures($this->getContainer()->get(UserPasswordHasherInterface::class)));

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        expect(iterator_to_array($facade->find()))->toHaveCount(2);
    });

    it('should successfully create user', function () {
        $store = StoreContext::create()();

        $this->save($store);

        $transfer = UserCreate::fromArray([
            'email' => faker()->email(),
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

        expect($user->email())->toEqual($transfer->email())
            ->and(iterator_to_array($repository->find()))->toHaveCount(1);

        $dbUser = $repository->findByEmail(new Email($transfer->email()));

        expect($user->roles())->toHaveCount(3)
            ->and($dbUser->roles())->toHaveCount(3)
            ->and($user->createdAt())->toBeInstanceOf(DateTimeImmutable::class)
            ->and($user->updatedAt())->toBeInstanceOf(DateTimeImmutable::class)
            ->and($user->stores())->toHaveCount(1)
            ->and($dbUser->stores())->toHaveCount(1);
    });

    it('should successfully delete user', function () {
        $user = UserContext::create()();

        $this->save($user);

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        $facade->delete(UserDelete::fromArray(['id' => (string) $user->id()]));

        expect(iterator_to_array($repository->find()))->toHaveCount(0);
    });

    it('successfully remove user and relations to stores', function () {
        $store = StoreContext::create()();
        $user = UserContext::create()();

        $user->addStore($store);

        $this->save($user);

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        /** @var UserRepositoryInterface $repository */
        $repository = $this->getContainer()->get(UserRepositoryInterface::class);

        /** @var StoreRepositoryInterface $storeRepository */
        $storeRepository = $this->getContainer()->get(StoreRepositoryInterface::class);

        $facade->delete(UserDelete::fromArray(['id' => (string) $user->id()]));

        expect(iterator_to_array($repository->find()))->toHaveCount(0)
            ->and(iterator_to_array($store->users()))->toHaveCount(0);

        $dbStore = $storeRepository->findOne($store->id());

        expect($dbStore->users())->toHaveCount(0);
    });

    it('should successfully handle attempt to remove not exising user', function () {
        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);

        $facade->delete(UserDelete::fromArray(['id' => 'not-existing']));
    })->expectException(NotFoundException::class);

    it('should successfully update user', function () {
        $user = UserContext::create()();
        $store = StoreContext::create()();

        $this->save($user, $store);

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

        expect($updatedUser->id())->toBe($clonedUser->id())
            ->and((string) $updatedUser->email())->toEqual('test@mail.com')
            ->and($updatedUser->roles())->toHaveCount(2)
            ->and(array_flip($updatedUser->roles()))->toHaveKey('ROLE')
            ->and($updatedUser->stores())->toHaveCount(1)
            ->and($updatedUser->stores()->get(0)->id())->toEqual($store->id());
    });

    it('should fails when try to update user with not existing store', function () {
        $user = UserContext::create()();

        $this->save($user);

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
    })->expectException(NotFoundException::class);

    it('should find user by id', function () {
        $user = UserContext::create()();

        $this->save($user);

        /** @var UserFacade $facade */
        $facade = $this->getContainer()->get(UserFacade::class);
        $dbUser = $facade->findById($user->id());

        expect($dbUser)->toEqual($user);
    });
});
