<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Payment\Form;

use App\Domain\Model\Gateway;
use App\Domain\Model\Payment;
use App\Domain\Model\QR;
use App\Domain\Model\Store;
use App\Domain\ValueObject\Log as DomainLog;
use App\Infrastructure\Form\Data\PropertyAccessorTrait;
use App\Infrastructure\Test\Faker\Generator;

final readonly class PaymentData
{
    use PropertyAccessorTrait;

    public function __construct(private Payment $payment)
    {
    }

    public function id(): string
    {
        return (string) $this->payment->id();
    }

    public function amount(): string
    {
        return (string) $this->payment->amount();
    }

    public function commission(): string
    {
        return (string) $this->payment->commission();
    }

    public function qrExists(): bool
    {
        return null !== $this->payment->qr();
    }

    public function qr(): ?string
    {
        if (!$this->qrExists()) {
            return null;
        }

        return (string) $this->payment->qr()->id();
    }

    public function store(): string
    {
        return (string) $this->payment->store()->id();
    }

    public function gateway(): string
    {
        return (string) $this->payment->gateway()->id();
    }

    public function logs(): array
    {
        return array_map(
            fn (DomainLog $log) => new Log(date('Y/m/d H:i', $log->time()->getTimestamp()), $log->value()),
            $this->payment->logs()
        );
    }
}
