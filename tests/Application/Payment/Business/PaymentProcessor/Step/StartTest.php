<?php

declare(strict_types = 1);

namespace App\Tests\Application\Payment\Business\PaymentProcessor\Step;

use App\Application\Contract\HttpClientInterface;
use App\Application\Payment\Business\PaymentProcessor\Step\Start;
use App\Application\Payment\Business\StateMachine\PaymentStatusHandler;
use App\Domain\Enum\PaymentStatusEnum;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Infrastructure\Test\Context\Model\PaymentContext;
use Mockery;
use Psr\Log\LoggerInterface;

final class StartTest extends AbstractUnitTestCase
{
    public function testShouldSuccessfullyAddInitializationLogRecordToPayment(): void
    {
        $payment = PaymentContext::create()();

        $context = ['id' => (string) $payment->id(), 'status' => PaymentStatusEnum::new->name,];

        $loggerMock = Mockery::mock(LoggerInterface::class);
        $loggerMock->shouldReceive('info')->once()->withArgs(['Handling payment', $context]);
        $loggerMock->shouldReceive('info')->once()->withArgs(['Payment chain completed', $context]);

        $httpClientMock = Mockery::mock(HttpClientInterface::class);
        $statusHandlerMock = Mockery::mock(PaymentStatusHandler::class);

        $step = new Start($httpClientMock, $loggerMock, $statusHandlerMock);

        $step->handle($payment);

        $this->assertCount(1, $payment->logs());
    }
}
