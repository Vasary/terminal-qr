<?php

declare(strict_types = 1);

namespace App\Tests\Application\Payment\Business\PaymentProcessor\Step;

use App\Application\Contract\HttpClientInterface;
use App\Application\Payment\Business\PaymentProcessor\Step\CreateTransaction;
use App\Application\Payment\Business\StateMachine\PaymentStatusHandler;
use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Enum\WorkflowTransitionEnum;
use App\Infrastructure\HTTP\Response\RegisterPaymentResponse;
use App\Infrastructure\Serializer\Serializer;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Infrastructure\Test\Context\Model\PaymentContext;
use Mockery;
use Psr\Log\LoggerInterface;

final class CreateTransactionTest extends AbstractUnitTestCase
{
    private const RESPONSE_WITH_QR = <<<JSON
{"result":{"status": "INTERIM_SUCCESS","qr":{"payload":"https://qr.nspk.ru/BS*******","imageUrl":"https://api.qrserver.com/v1/create-qr-code/?size=450x450&data=https://qr.nspk.ru/BS1**********************"}}}
JSON;

    public function testShouldSuccessfullyCreateTransactionAndChangePaymentStatusToAwaiting(): void
    {
        $paymentContext = PaymentContext::create();
        $paymentContext->status = PaymentStatusEnum::token;
        $paymentContext->token = 'token';

        $payment = $paymentContext();

        $context = ['id' => (string) $payment->id(), 'status' => PaymentStatusEnum::token->name];

        $loggerMock = Mockery::mock(LoggerInterface::class);
        $loggerMock->shouldReceive('info')->once()->withArgs(
            ['Attempt to register transaction at external provider', $context]
        );

        $loggerMock->shouldReceive('info')->once()->withArgs(
            ['Accepted successful registration status. Continue', $context]
        );
        $loggerMock->shouldReceive('info')->once()->withArgs(
            ['Payment chain completed', ['id' => (string) $payment->id(), 'status' => PaymentStatusEnum::awaiting->name]]
        );

        /** @var RegisterPaymentResponse $registerPaymentResponse */
        $registerPaymentResponse = Serializer::create()->deserialize(
            self::RESPONSE_WITH_QR,
            RegisterPaymentResponse::class
        );

        $httpClientMock = Mockery::mock(HttpClientInterface::class);
        $httpClientMock
            ->shouldReceive('registerPayment')
            ->once()
            ->withArgs([$payment->gateway()->portal(), $payment->token()])
            ->andReturn($registerPaymentResponse);

        $statusHandlerMock = $this->getContainer()->get(PaymentStatusHandler::class);
        $step = new CreateTransaction($httpClientMock, $loggerMock, $statusHandlerMock);

        $step->handle($payment);

        $this->assertNotNull($payment->qr());
        $this->assertCount(3, $payment->logs());
        $this->assertEquals(PaymentStatusEnum::awaiting, $payment->status());
    }
}
