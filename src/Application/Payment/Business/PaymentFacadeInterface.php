<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business;

use App\Domain\Model\Payment;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\SearchCriteria;

interface PaymentFacadeInterface
{
    public function findByCriteria(SearchCriteria $criteria): Pagination;

    public function create(int $amount, Id $storeId, Id $gatewayId): Payment;

    public function findById(Id $id): ?Payment;

    public function handle(Id $id): Payment;
}
