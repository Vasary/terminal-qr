<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business;

use App\Application\Payment\Business\PaymentProcessor\PaymentProcessor;
use App\Application\Payment\Business\Reader\PaymentReader;
use App\Domain\Model\Payment;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\SearchCriteria;

final readonly class PaymentFacade implements PaymentFacadeInterface
{
    public function __construct(private PaymentReader $reader, private PaymentProcessor $processor,)
    {
    }

    public function findByCriteria(SearchCriteria $criteria): Pagination
    {
        return $this->reader->findByCriteria($criteria);
    }

    public function findById(Id $id): ?Payment
    {
        return $this->reader->findOneById($id);
    }

    public function handle(Id $id): Payment
    {
        return $this->processor->handle($id);
    }

    public function create(int $amount, Id $storeId, Id $gatewayId): Payment
    {
        return $this->processor->create($amount, $storeId, $gatewayId);
    }
}
