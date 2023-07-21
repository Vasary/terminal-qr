<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\PaymentProcessor;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Application\Payment\Business\PaymentProcessor\Step\Start;
use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Payment;
use App\Domain\Repository\PaymentRepositoryInterface;
use App\Domain\ValueObject\Id;
use Psr\Log\LoggerInterface;
use Throwable;

final readonly class PaymentProcessor
{
    public function __construct(
        private StoreFacadeInterface $storeFacade,
        private GatewayFacadeInterface $gatewayFacade,
        private PaymentRepositoryInterface $repository,
        private Start $start,
        private LoggerInterface $logger,
    )
    {
    }

    public function create(int $amount, Id $storeId, Id $gatewayId): Payment
    {
        $store = $this->storeFacade->findById($storeId);
        $gateway = $this->gatewayFacade->findById($gatewayId);

        $payment = $this->repository->create($amount, $store, $gateway);
        $payment->addLog('Payment created');

        $this->repository->update($payment);

        return $payment;
    }

    /**
     * @throws NotFoundException
     */
    public function handle(Id $paymentId): Payment
    {
        $payment = $this->repository->findOne($paymentId);

        if (null === $payment) {
            throw new NotFoundException(Payment::class, $paymentId);
        }

        try {
            $this->start->handle($payment);
        } catch (Throwable $exception) {
            $this->logger->critical($exception->getMessage());
        }

        $this->repository->update($payment);

        return $payment;
    }
}
