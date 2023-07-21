<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\PaymentProcessor\Step;

use App\Domain\Enum\WorkflowTransitionEnum;
use App\Domain\Model\Payment;
use App\Infrastructure\HTTP\Exception\TokenizationException;

final class Tokenize extends AbstractStep
{
    public function handle(Payment $payment): void
    {
        if ($payment->status()->isInitiated()) {
            $this->tokenize($payment);
        }

        parent::handle($payment);
    }

    private function tokenize(Payment $payment): void
    {
        $this->logger->info('starting tokenization process');

        try {
            $token = $this->client->getToken($payment->gateway()->portal());
        } catch (TokenizationException $exception) {
            $this->logger->error($exception->getMessage(), $this->getContext($payment));
            $this->logger->notice('set payment to failure status');

            $this->statusHandler->handle($payment, WorkflowTransitionEnum::failure);

            return;
        }

        $this->logger->info('Token ' . $token . ' caught. Attache to payment.');
        $payment->withToken($token);

        $this->statusHandler->handle($payment, WorkflowTransitionEnum::tokenized);
    }
}
