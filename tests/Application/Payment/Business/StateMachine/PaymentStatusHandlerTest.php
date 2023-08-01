<?php

declare(strict_types = 1);

namespace App\Tests\Application\Payment\Business\StateMachine;

use App\Application\Payment\Business\StateMachine\PaymentStatusHandler;
use App\Domain\Enum\WorkflowTransitionEnum;
use App\Domain\Exception\WorkflowException;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Infrastructure\Test\Context\Model\PaymentContext;

final class PaymentStatusHandlerTest extends AbstractUnitTestCase
{
    public function testShouldFailChangeStateOfPayment(): void
    {
        $payment = PaymentContext::create()();

        $this->expectException(WorkflowException::class);

        /** @var PaymentStatusHandler $facade */
        $handler = $this->getContainer()->get(PaymentStatusHandler::class);

        $handler->handle($payment, WorkflowTransitionEnum::alert);
    }

    public function testShouldSuccessfullyChangeStateOfPayment(): void
    {
        $payment = PaymentContext::create()();

        /** @var PaymentStatusHandler $facade */
        $handler = $this->getContainer()->get(PaymentStatusHandler::class);

        $handler->handle($payment, WorkflowTransitionEnum::tokenized);

        $this->assertCount(2, $payment->logs());
    }
}
