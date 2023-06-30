<?php

declare(strict_types = 1);

namespace App\Application\Gateway\Business;

use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\GatewayCreate;
use App\Shared\Transfer\GatewayDelete;
use App\Shared\Transfer\GatewayUpdate;
use App\Shared\Transfer\SearchCriteria;
use Generator;

interface GatewayFacadeInterface
{
    public function findById(Id $id): ?Gateway;

    public function create(GatewayCreate $transfer): Gateway;

    public function find(): Generator;

    public function update(GatewayUpdate $transfer): Gateway;

    public function findByCriteria(SearchCriteria $searchCriteria): Pagination;

    public function delete(GatewayDelete $param): void;

    public function findByKey(Key $param): ?Gateway;
}
