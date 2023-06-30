<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Model\Gateway;
use App\Domain\Model\Payment;
use App\Domain\Model\Store;
use App\Domain\ValueObject\Id;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface PaymentRepositoryInterface
{
    public function findByCriteria(array $fields, array $orderBy, int $page, int $limit): Paginator;

    public function findOne(Id $id): ?Payment;

    public function update(Payment $payment): void;

    public function create(int $amount, Store $store, Gateway $gateway): Payment;
}
