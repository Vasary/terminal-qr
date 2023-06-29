<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\Writer;

use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Model\Payment;
use App\Domain\Repository\PaymentRepositoryInterface;

final readonly class PaymentWriter
{
    public function __construct(private PaymentRepositoryInterface $repository)
    {
    }

    public function changeStatus(Payment $payment, PaymentStatusEnum $status): Payment
    {
        $payment->withStatus($status);

        $this->repository->update($payment);

        return $payment;
    }
}
