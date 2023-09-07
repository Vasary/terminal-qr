<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Form;

use App\Infrastructure\Assert\Currency;
use App\Infrastructure\Assert\Hostname;
use App\Infrastructure\Assert\Length;
use App\Infrastructure\Assert\NotBlank;
use App\Infrastructure\Assert\Url;

final class Data
{
    #[NotBlank]
    #[Url]
    public string $callback;

    #[Currency]
    #[NotBlank]
    public string $currency;

    #[Length(min: 1, max: 255)]
    #[NotBlank]
    public string $portal;

    #[Hostname]
    #[NotBlank]
    public string $host;

    #[Length(min: 1, max: 255)]
    #[NotBlank]
    public string $title;

    public array $credentials = [];

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'callback' => $this->callback,
            'host' => $this->host,
            'portal' => $this->portal,
            'currency' => $this->currency,
        ];
    }
}
