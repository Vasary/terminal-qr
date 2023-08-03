<?php

declare(strict_types = 1);

namespace App\Tests\Application\Payment\Business\PaymentProcessor\Step;

use App\Application\Contract\HttpClientInterface;
use App\Application\Payment\Business\PaymentProcessor\Step\Tokenize;
use App\Application\Payment\Business\StateMachine\PaymentStatusHandler;
use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Enum\WorkflowTransitionEnum;
use App\Infrastructure\HTTP\Exception\TokenizationException;
use App\Infrastructure\Test\AbstractWebTestCase;
use App\Infrastructure\Test\Context\Model\PaymentContext;
use Mockery;
use Psr\Log\LoggerInterface;

final class TokenizeTest extends AbstractWebTestCase
{
    public function testShouldSuccessfullyUpdatePaymentStatus(): void
    {
        $mockedToken = 'mockedToken';
        $payment = PaymentContext::create()();

        $context = ['id' => (string) $payment->id(), 'status' => PaymentStatusEnum::new->name,];

        $loggerMock = Mockery::mock(LoggerInterface::class);
        $loggerMock->shouldReceive('info')->once()->withArgs(['Starting tokenization process', $context]);
        $loggerMock->shouldReceive('info')->once()->withArgs(
            ['Token ' . $mockedToken . ' caught. Attache to payment.', $context]
        );
        $loggerMock->shouldReceive('info')->once()->withArgs(
            ['Payment chain completed', ['id' => 'abba44b6-e3e5-477c-a3a1-b78dce149d4d', 'status' => 'token']]
        );

        $httpClientMock = Mockery::mock(HttpClientInterface::class);
        $httpClientMock
            ->shouldReceive('getToken')
            ->once()
            ->withArgs([$payment->gateway()->portal()])
            ->andReturn($mockedToken)
        ;

        $statusHandlerMock = $this->getContainer()->get(PaymentStatusHandler::class);

        $step = new Tokenize($httpClientMock, $loggerMock, $statusHandlerMock);

        $step->handle($payment);

        $this->assertNotNull($payment->token());
        $this->assertEquals($mockedToken, (string) $payment->token());
        $this->assertCount(3, $payment->logs());
    }

    public function testShouldFailsIfHttpClientThrowAnyException(): void
    {
        $paymentContext = PaymentContext::create();
        $paymentContext->token = null;

        $payment = $paymentContext();

        $context = ['id' => (string) $payment->id(), 'status' => PaymentStatusEnum::new->name,];

        $loggerMock = Mockery::mock(LoggerInterface::class);
        $loggerMock->shouldReceive('info')->once()->withArgs(['Starting tokenization process', $context]);
        $loggerMock->shouldReceive('error')->once()->withArgs(['Tokenization', $context]);
        $loggerMock->shouldReceive('notice')->once()->withArgs(['Set payment to failure status', $context]);
        $loggerMock->shouldReceive('info')->once()->withArgs(
            ['Payment chain completed', ['id' => 'abba44b6-e3e5-477c-a3a1-b78dce149d4d', 'status' => 'failure']]
        );

        $httpClientMock = Mockery::mock(HttpClientInterface::class);
        $httpClientMock
            ->shouldReceive('getToken')
            ->once()
            ->andThrow(new TokenizationException('Tokenization'))
        ;

        $statusHandlerMock = $this->getContainer()->get(PaymentStatusHandler::class);

        $step = new Tokenize($httpClientMock, $loggerMock, $statusHandlerMock);

        $step->handle($payment);

        $this->assertNull($payment->token());
        $this->assertCount(3, $payment->logs());
    }
}
