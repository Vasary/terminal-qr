<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Gateway\Form;

use Symfony\Component\Validator\Constraints as Assert;

final class Data
{
    #[Assert\NotBlank]
    #[Assert\Url]
    public string $callback;

    #[Assert\Currency]
    #[Assert\NotBlank]
    public string $currency;

    #[Assert\Length(min: 1, max: 255)]
    #[Assert\NotBlank]
    public string $portal;

    #[Assert\Domain]
    #[Assert\NotBlank]
    public string $host;

    #[Assert\Length(min: 1, max: 255)]
    #[Assert\NotBlank]
    public string $title;

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
