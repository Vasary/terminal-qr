<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\StateMachine;

use App\Domain\Enum\WorkflowTransitionEnum;
use App\Domain\Exception\WorkflowException;
use App\Domain\Model\Payment;
use Psr\Log\LoggerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class PaymentStatusHandler
{
    public function __construct(private WorkflowInterface $paymentStateMachine, private LoggerInterface $logger,)
    {
    }

    public function handle(Payment $payment, WorkflowTransitionEnum $transition): Payment
    {
        $payment->addLog('Attempt to change status via transition: ' . $transition->value);

        if (!$this->paymentStateMachine->can($payment, $transition->name)) {
            throw new WorkflowException();
        }

        $this->paymentStateMachine->apply($payment, $transition->name);

        $logMessage = 'Transition ' . $transition->value . ' has been successfully applied';

        $this->logger->info($logMessage);
        $payment->addLog($logMessage);

        return $payment;
    }
}
