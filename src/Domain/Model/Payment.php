<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Log;
use App\Domain\ValueObject\PaymentParameter;
use DateTimeImmutable;

class Payment
{
    private Id $id;
    private DateTimeImmutable $updatedAt;
    private DateTimeImmutable $createdAt;
    private ?QR $qrCode;

    /** @var Log[] */
    private array $logs;

    /** @var PaymentParameter[] */
    private array $parameters;

    public function __construct(
        private readonly int $amount,
        private readonly int $commission,
        private PaymentStatusEnum $status,
        private readonly Callback $callback,
        private readonly Currency $currency,
        private readonly Gateway $gateway,
        private readonly Store $store,
        DateTimeImmutable $now,
    )
    {
        $this->id = Id::create();
        $this->qrCode = null;
        $this->logs = [];
        $this->parameters = [];
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function commission(): int
    {
        return $this->commission;
    }

    public function status(): PaymentStatusEnum
    {
        return $this->status;
    }

    public function callback(): Callback
    {
        return $this->callback;
    }

    public function gateway(): Gateway
    {
        return $this->gateway;
    }

    public function store(): Store
    {
        return $this->store;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function withQR(QR $qr): void
    {
        $this->qrCode = $qr;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function qr(): ?QR
    {
        return $this->qrCode;
    }

    public function withStatus(PaymentStatusEnum $status): void
    {
        $this->status = $status;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function addLog(string $log): void
    {
        $this->logs[] = new Log($log, new DateTimeImmutable());
        $this->updatedAt = new DateTimeImmutable();
    }

    public function addParameter(string $parameter, ?string $value): self
    {
        $this->parameters[] = new PaymentParameter($parameter, $value);
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function logs(): array
    {
        return $this->logs;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }
}
