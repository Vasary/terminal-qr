<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business;

use App\Application\Payment\Business\PaymentProcessor\PaymentProcessor;
use App\Application\Payment\Business\Reader\PaymentReader;
use App\Application\Payment\Business\Writer\PaymentWriter;
use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Model\Payment;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\SearchCriteria;
use Generator;

final readonly class PaymentFacade implements PaymentFacadeInterface
{
    public function __construct(
        private PaymentReader $reader,
        private PaymentWriter $writer,
        private PaymentProcessor $processor,
    )
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

    public function changeStatus(Payment $payment, PaymentStatusEnum $status): Payment
    {
        return $this->writer->changeStatus($payment, $status);
    }

    public function create(int $amount, Id $storeId, Id $gatewayId): Payment
    {
        return $this->processor->create($amount, $storeId, $gatewayId);
    }
}
