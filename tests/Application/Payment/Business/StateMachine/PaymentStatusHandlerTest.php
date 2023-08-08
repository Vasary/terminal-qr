<?php

declare(strict_types = 1);

namespace App\Tests\Application\Payment\Business\StateMachine;

use App\Application\Payment\Business\StateMachine\PaymentStatusHandler;
use App\Domain\Enum\WorkflowTransitionEnum;
use App\Domain\Exception\WorkflowException;
use App\Infrastructure\Test\Context\Model\PaymentContext;

it('should fail to change the state of payment', function () {
    $payment = PaymentContext::create()();

    $this->expectException(WorkflowException::class);

    /** @var PaymentStatusHandler $facade */
    $handler = $this->getContainer()->get(PaymentStatusHandler::class);

    $handler->handle($payment, WorkflowTransitionEnum::alert);
});

it('should successfully change the state of payment', function () {
    $payment = PaymentContext::create()();

    /** @var PaymentStatusHandler $facade */
    $handler = $this->getContainer()->get(PaymentStatusHandler::class);

    $handler->handle($payment, WorkflowTransitionEnum::tokenized);

    expect($payment->logs())->toHaveCount(2);
});
