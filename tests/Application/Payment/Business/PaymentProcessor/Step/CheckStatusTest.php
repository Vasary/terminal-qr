<?php

declare(strict_types = 1);

namespace App\Tests\Application\Payment\Business\PaymentProcessor\Step;

use App\Application\Contract\HttpClientInterface;
use App\Application\Payment\Business\PaymentProcessor\Step\CheckPaymentStatus;
use App\Application\Payment\Business\PaymentProcessor\Step\Start;
use App\Application\Payment\Business\StateMachine\PaymentStatusHandler;
use App\Domain\Enum\PaymentStatusEnum;
use App\Infrastructure\Test\AbstractWebTestCase;
use App\Infrastructure\Test\Context\Model\PaymentContext;
use Mockery;
use Psr\Log\LoggerInterface;

final class CheckStatusTest extends AbstractWebTestCase
{
    public function testShouldSuccessfullyCheckPaymentStatus(): void
    {
        $paymentContext = PaymentContext::create();
        $paymentContext->status = PaymentStatusEnum::awaiting;

        $payment = $paymentContext();

        $context = ['id' => (string) $payment->id(), 'status' => PaymentStatusEnum::awaiting->name,];

        $loggerMock = Mockery::mock(LoggerInterface::class);
        $loggerMock
            ->shouldReceive('info')
            ->once()
            ->withArgs(['Attempt to check payment status', $context]);

        $loggerMock->shouldReceive('info')->once()->withArgs(
            ['Payment chain completed', $context]
        );

        $httpClientMock = Mockery::mock(HttpClientInterface::class);
        $statusHandlerMock = Mockery::mock(PaymentStatusHandler::class);

        $step = new CheckPaymentStatus($httpClientMock, $loggerMock, $statusHandlerMock);

        $step->handle($payment);

        $this->assertCount(0, $payment->logs());
    }
}
