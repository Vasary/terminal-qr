<?php

declare(strict_types=1);

namespace App\Application\Store\Business\Writer;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Application\User\Business\UserFacadeInterface;
use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Store;
use App\Domain\Repository\StoreRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Shared\Transfer\StoreCreate;
use App\Shared\Transfer\StoreDelete;
use App\Shared\Transfer\StoreUpdate;

final readonly class StoreWriter
{
    public function __construct(
        private StoreRepositoryInterface $repository,
        private GatewayFacadeInterface $gatewayFacade,
    )
    {
    }

    public function write(StoreCreate $transfer): Store
    {
        return $this->repository->create(
            $this->generateKey($transfer),
            $transfer->title(),
            $transfer->description()
        );
    }

    /**
     * @throws NotFoundException
     */
    public function update(StoreUpdate $transfer): Store
    {
        $id = Id::fromString($transfer->id());
        $store = $this->getStore($id);

        $store
            ->withTitle($transfer->title())
            ->withDescription($transfer->description());

        $this->repository->update($store);

        return $store;
    }

    /**
     * @throws NotFoundException
     */
    public function delete(StoreDelete $transfer): void
    {
        $id = Id::fromString($transfer->id());
        $store = $this->getStore($id);

        $this->repository->delete($store);
    }

    /**
     * @throws NotFoundException
     */
    public function addGateway(Id $storeId, Id $gatewayId): Store
    {
        $gateway = $this->gatewayFacade->findById($gatewayId);
        $store = $this->getStore($storeId);

        $store->addGateway($gateway);
        $this->repository->update($store);

        return $store;
    }

    /**
     * @throws NotFoundException
     */
    public function removeGateway(Id $storeId, Id $gatewayId): Store
    {
        $gateway = $this->gatewayFacade->findById($gatewayId);
        $store = $this->getStore($storeId);

        $store->removeGateway($gateway);
        $gateway->removeStore($store);

        $this->repository->update($store);

        return $store;
    }

    /**
     * @throws NotFoundException
     */
//    public function addUser(Id $storeId, Id $userId): Store
//    {
////        $user = $this->userFacade->findById($userId);
//        $store = $this->getStore($storeId);
//
////        $store->addUser($user);
////        $this->repository->update($store);
//
//        return $store;
//    }

    /**
     * @throws NotFoundException
     */
    private function getStore(Id $id): Store
    {
        $store = $this->repository->findOne($id);

        if (null === $store) {
            throw new NotFoundException(Store::class, $id);
        }

        return $store;
    }

    private function generateKey(StoreCreate $transfer): string
    {
        return md5($transfer->title() . $transfer->description() . srand(0, 99));
    }
}
