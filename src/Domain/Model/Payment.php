<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Id;
use DateTimeImmutable;

class Payment
{
    private Id $id;
    private DateTimeImmutable $updatedAt;
    private DateTimeImmutable $createdAt;

    private ?QR $qrCode;

    public function __construct(
        private readonly int $amount,
        private readonly int $commission,
        private readonly int $status,
        private readonly string $callbackUrl,
        private readonly Id $gateway,
        private readonly Id $store,
    )
    {
        $this->id = Id::create();
        $this->qrCode = null;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
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

    public function status(): int
    {
        return $this->status;
    }

    public function callback(): string
    {
        return $this->callbackUrl;
    }

    public function gateway(): Id
    {
        return $this->gateway;
    }

    public function store(): Id
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
}
