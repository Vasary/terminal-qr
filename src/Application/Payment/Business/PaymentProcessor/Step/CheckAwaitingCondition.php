<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\PaymentProcessor\Step;

use App\Domain\Enum\WorkflowTransitionEnum;
use App\Domain\Model\Payment;

final class CheckAwaitingCondition extends AbstractStep
{
    public function handle(Payment $payment): void
    {
        if ($payment->status()->isRegistered()) {
            $this->markAsAwaiting($payment);
        }

        parent::handle($payment);
    }

    public function markAsAwaiting(Payment $payment): void
    {
        $this->logger->info('Moving payment to status awaiting status');

        $this->statusHandler->handle($payment, WorkflowTransitionEnum::wait);
    }
}
