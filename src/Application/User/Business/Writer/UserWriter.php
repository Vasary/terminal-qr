<?php

declare(strict_types = 1);

namespace App\Application\User\Business\Writer;

use App\Application\Contract\UserPasswordHasherInterface;
use App\Application\Store\Business\StoreFacadeInterface;
use App\Application\User\User as ApplicationUser;
use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Store;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Shared\Transfer\UserCreate;
use App\Shared\Transfer\UserDelete;
use App\Shared\Transfer\UserUpdate;

final readonly class UserWriter
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private UserRepositoryInterface $repository,
        private StoreFacadeInterface $storeFacade,
    )
    {
    }

    public function create(UserCreate $transfer): User
    {
        $roles = $this->getRoles($transfer->roles());

        $domainUser = $this->repository->create($transfer->email(), $roles, $transfer->password());
        $applicationUser = new ApplicationUser($domainUser);

        $domainUser->withPassword(
            $this->hasher->hashPassword($applicationUser, $transfer->password())
        );

        foreach ($transfer->stores() as $storeId) {
            $store = $this->storeFacade->findById(Id::fromString($storeId));

            $domainUser->addStore($store);
        }

        $this->repository->update($domainUser);

        return $domainUser;
    }

    /**
     * @throws NotFoundException
     */
    public function delete(UserDelete $transfer): void
    {
        $id = Id::fromString($transfer->id());
        $user = $this->getUser($id);

        $user->removeStores();

        $this->repository->delete($user);
    }

    /**
     * @throws NotFoundException
     */
    public function update(UserUpdate $transfer): User
    {
        $user = $this->getUser(Id::fromString($transfer->id()));

        $user->withEmail($transfer->email());

        $user->cleanRoles();
        foreach ($this->getRoles($transfer->roles()) as $role) {
            $user->addRole($role);
        }

        $user->removeStores();
        foreach ($transfer->stores() as $storeId) {
            $id = Id::fromString($storeId);

            if (null === $store = $this->storeFacade->findById($id)) {
                throw new NotFoundException(Store::class, $id);
            }

            $user->addStore($store);
        }

        if ('' !== $transfer->password()) {
            $user->withPassword(
                $this->hasher->hashPassword(new ApplicationUser($user), $transfer->password())
            );
        }

        $this->repository->update($user);

        return $user;
    }

    /**
     * @throws NotFoundException
     */
    private function getUser(Id $id): User
    {
        $user = $this->repository->findById($id);

        if (null === $user) {
            throw new NotFoundException(User::class, $id);
        }

        return $user;
    }

    private function getRoles(array $roles): array
    {
        return array_merge(['ROLE_USER'], $roles);
    }
}
