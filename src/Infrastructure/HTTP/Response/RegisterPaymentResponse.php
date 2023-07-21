<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP\Response;

use App\Infrastructure\HTTP\Response\CheckPaymentStatusResponse\Result;
use App\Infrastructure\HTTP\Response\CheckPaymentStatusResponse\Src;

final class RegisterPaymentResponse
{
    private ?string $token = null;
    private ?int $startedAt = null;
    private ?int $finishedAt = null;
    private ?string $state = null;
    private ?string $paymentId = null;
    private ?int $amount = null;
    private ?int $commission = null;
    private ?string $currency = null;
    private ?string $portalType = null;
    private ?string $type = null;

    private ?Src $src = null;
    private ?Result $result = null;

    public function token(): ?string
    {
        return $this->token;
    }

    public function startedAt(): ?int
    {
        return $this->startedAt;
    }

    public function finishedAt(): ?int
    {
        return $this->finishedAt;
    }

    public function state(): ?string
    {
        return $this->state;
    }

    public function paymentId(): ?string
    {
        return $this->paymentId;
    }

    public function amount(): ?int
    {
        return $this->amount;
    }

    public function commission(): ?int
    {
        return $this->commission;
    }

    public function currency(): ?string
    {
        return $this->currency;
    }

    public function portalType(): ?string
    {
        return $this->portalType;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function src(): ?Src
    {
        return $this->src;
    }

    public function result(): ?Result
    {
        return $this->result;
    }
}
