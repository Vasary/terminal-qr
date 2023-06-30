<?php

declare(strict_types=1);

namespace App\Application\Payment\Business\PaymentProcessor;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\Model\Payment;
use App\Domain\Model\QR;
use App\Domain\Repository\PaymentRepositoryInterface;
use App\Domain\ValueObject\Id;

final readonly class PaymentProcessor
{
    public function __construct(
        private StoreFacadeInterface $storeFacade,
        private GatewayFacadeInterface $gatewayFacade,
        private PaymentRepositoryInterface $repository,
    ) {}

    public function create(int $amount, Id $storeId, Id $gatewayId): Payment
    {
        $store = $this->storeFacade->findById($storeId);
        $gateway = $this->gatewayFacade->findById($gatewayId);

        $payment = $this->repository->create($amount, $store, $gateway);
        $payment->addLog('Payment request initiated');

        $this->repository->update($payment);

        $this->registerPaymentInExternalSystem($payment);

        $this->repository->update($payment);

        return $payment;
    }

    private function registerPaymentInExternalSystem(Payment $payment): void
    {
        $payment->withQR(new QR('test.json', 'http://'));
    }
}
