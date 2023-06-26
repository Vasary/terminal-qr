<?php

declare(strict_types = 1);

namespace App\Application\Store\Business;

use App\Application\Store\Business\Reader\StoreReader;
use App\Application\Store\Business\Writer\StoreWriter;
use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Store;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\SearchCriteria;
use App\Shared\Transfer\StoreDelete;
use App\Shared\Transfer\StoreUpdate;
use Generator;
use App\Shared\Transfer\StoreCreate;

final readonly class StoreFacade implements StoreFacadeInterface
{
    public function __construct(private StoreWriter $writer, private StoreReader $reader,)
    {
    }

    public function create(StoreCreate $transfer): Store
    {
        return $this->writer->write($transfer);
    }

    public function find(): Generator
    {
        return $this->reader->all();
    }

    public function findByCriteria(SearchCriteria $criteria): Pagination
    {
        return $this->reader->findByCriteria($criteria);
    }

    /**
     * @throws NotFoundException
     */
    public function update(StoreUpdate $transfer): Store
    {
        return $this->writer->update($transfer);
    }

    /**
     * @throws NotFoundException
     */
    public function delete(StoreDelete $transfer): void
    {
        $this->writer->delete($transfer);
    }

    public function findById(Id $id): ?Store
    {
        return $this->reader->findById($id);
    }

    /**
     * @throws NotFoundException
     */
//    public function addUser(Id $storeId, Id $userId): Store
//    {
//        return $this->writer->addUser($storeId, $userId);
//    }

    /**
     * @throws NotFoundException
     */
    public function addGateway(Id $storeId, Id $gatewayId): Store
    {
        return $this->writer->addGateway($storeId, $gatewayId);
    }

    /**
     * @throws NotFoundException
     */
    public function removeGateway(Id $storeId, Id $gatewayId): Store
    {
        return $this->writer->removeGateway($storeId, $gatewayId);
    }
}
