<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\PaymentProcessor\Step;

use App\Domain\Model\Payment;

final class Start extends AbstractStep
{
    public function handle(Payment $payment): void
    {
        $this->logger->info('Handling payment', $this->getContext($payment));

        $payment->addLog('Handling payment');

        parent::handle($payment);
    }
}
