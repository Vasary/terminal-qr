<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\PaymentProcessor\Step;

use App\Domain\Model\Payment;

interface StepInterface
{
    public function setNext(AbstractStep $handler): AbstractStep;

    public function handle(Payment $payment): void;
}
