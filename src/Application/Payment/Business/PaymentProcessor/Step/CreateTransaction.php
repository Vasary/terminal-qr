<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\PaymentProcessor\Step;

use App\Domain\Enum\WorkflowTransitionEnum;
use App\Domain\Model\Payment;
use App\Domain\Model\QR;
use App\Infrastructure\HTTP\Exception\TransactionRegistrationException;
use App\Infrastructure\HTTP\Response\RegisterPaymentResponse;
use function App\Infrastructure\DateTime\now;

final class CreateTransaction extends AbstractStep
{
    private const TRANSACTION_REGISTERED_STATUS = 'INTERIM_SUCCESS';
    private const TRANSACTION_REGISTRATION_FAILURE_STATUS = 'FAILED';

    public function handle(Payment $payment): void
    {
        if ($payment->status()->isTokenized()) {
            $this->createTransaction($payment);
        }

        parent::handle($payment);
    }

    private function createTransaction(Payment $payment): void
    {

//        $this->logger->info('Attempt to register transaction at external provider', $this->getContext($payment));
//
//        try {
//            $response = $this->client->registerPayment($payment->gateway()->portal(), $payment->token());
//        } catch (TransactionRegistrationException $exception) {
//            $this->logger->error($exception->getMessage(), $this->getContext($payment));
//            $this->logger->notice('Set payment to failure status', $this->getContext($payment));
//
//            $this->statusHandler->handle($payment, WorkflowTransitionEnum::failure);
//
//            return;
//        }
//
//
//
//        match($response->result()->status()) {
//            self::TRANSACTION_REGISTERED_STATUS => $this->handleSuccess($response, $payment),
//            self::TRANSACTION_REGISTRATION_FAILURE_STATUS => $this->handleFailure($response, $payment),
//        };
    }

    private function handleSuccess(RegisterPaymentResponse $response, Payment $payment): void
    {
        $this->logger->info('Accepted successful registration status. Continue', $this->getContext($payment));

        $qr = $response->result()->qr();

        $payment->withQR(new QR($qr->payload(), $qr->imageUrl(), now()));
        $payment->addLog('QR code caught');

        $this->statusHandler->handle($payment, WorkflowTransitionEnum::registered);
    }

    private function handleFailure(RegisterPaymentResponse $response, Payment $payment): void
    {
        $this->logger->info(
            'Accepted failure registration status. Stopping. Move payment to failure',
            $this->getContext($payment)
        );

        $this->statusHandler->handle($payment, WorkflowTransitionEnum::failure);
    }
}
