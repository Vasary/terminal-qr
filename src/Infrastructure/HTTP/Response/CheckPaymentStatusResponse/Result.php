<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP\Response\CheckPaymentStatusResponse;

final class Result
{
    private ?string $status = null;
    private ?string $extendedCode = null;
    private ?string $message = null;
    private ?string $trxId = null;
    private ?Qr $qr = null;

    public function status(): ?string
    {
        return $this->status;
    }

    public function extendedCode(): ?string
    {
        return $this->extendedCode;
    }

    public function message(): ?string
    {
        return $this->message;
    }

    public function trxId(): ?string
    {
        return $this->trxId;
    }

    public function qr(): ?Qr
    {
        return $this->qr;
    }
}
