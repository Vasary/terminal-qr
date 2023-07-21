<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\PaymentProcessor\Step;

use App\Application\Contract\HttpClientInterface;
use App\Application\Payment\Business\StateMachine\PaymentStatusHandler;
use App\Domain\Model\Payment;
use Psr\Log\LoggerInterface;

abstract class AbstractStep implements StepInterface
{
    private ?AbstractStep $nextHandler = null;

    public function __construct(
        protected readonly HttpClientInterface $client,
        protected readonly LoggerInterface $logger,
        protected readonly PaymentStatusHandler $statusHandler,
    )
    {
    }

    public function setNext(AbstractStep $handler): AbstractStep
    {
        return $this->nextHandler = $handler;
    }

    public function handle(Payment $payment): void
    {
        if (null === $this->nextHandler) {
            $this->logger->info('Payment chain completed', $this->getContext($payment));
        }

        $this->nextHandler?->handle($payment);
    }

    protected function getContext(Payment $payment): array
    {
        return [
            'id' => (string) $payment->id(),
            'status' => $payment->status()->name,
        ];
    }
}
