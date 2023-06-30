<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Terminal\Form;

use Symfony\Component\Validator\Constraints as Assert;

final class Data
{
    #[Assert\GreaterThan(0)]
    #[Assert\NotBlank]
    public int $amount;

    #[Assert\NotBlank]
    public string $gateway;

    #[Assert\NotBlank]
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
