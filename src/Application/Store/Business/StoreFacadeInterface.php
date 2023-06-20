<?php

declare(strict_types = 1);

namespace App\Application\Store\Business;

use App\Domain\Model\Store;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\SearchCriteria;
use App\Shared\Transfer\StoreCreate;
use App\Shared\Transfer\StoreDelete;
use App\Shared\Transfer\StoreUpdate;
use Generator;

interface StoreFacadeInterface
{
    public function create(StoreCreate $transfer): Store;

    public function find(): Generator;

    public function findById(Id $id): ?Store;

    public function findByCriteria(SearchCriteria $criteria): Pagination;

    public function update(StoreUpdate $transfer): Store;

    public function delete(StoreDelete $transfer): void;

    public function addGateway(Id $storeId, Id $gatewayId): Store;

    public function removeGateway(Id $storeId, Id $gatewayId): Store;
}
