<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Terminal\Form;

use App\Infrastructure\Assert\GreaterThan;
use App\Infrastructure\Assert\NotBlank;

final class Data
{
    #[GreaterThan(0)]
    #[NotBlank]
    public int $amount;

    #[NotBlank]
    public string $amountMask;

    #[NotBlank]
    public string $gateway;

    #[NotBlank]
    public string $store;

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'gateway' => $this->gateway,
            'store' => $this->store,
        ];
    }
}
