<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\PaymentProcessor\Step;

use App\Domain\Model\Payment;

final class CheckPaymentStatus extends AbstractStep
{
    public function handle(Payment $payment): void
    {
        if ($payment->status()->isAwaiting()) {
            $this->checkPaymentStatus($payment);
        }

        parent::handle($payment);
    }

    public function checkPaymentStatus(Payment $payment): void
    {
        $this->logger->info('Attempt to check payment status', $this->getContext($payment));
    }
}
