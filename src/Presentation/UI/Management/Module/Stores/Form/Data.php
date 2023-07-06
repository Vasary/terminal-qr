<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Stores\Form;

use App\Infrastructure\Assert\IdCollection;
use App\Infrastructure\Assert\Length;
use App\Infrastructure\Assert\NotBlank;

final class Data
{
    #[Length(min: 1, max: 50)]
    #[NotBlank]
    public string $title;

    #[IdCollection(true)]
    #[NotBlank]
    public array $gateways;

    #[NotBlank]
    public string $description;

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'gateways' => $this->gateways,
            'description' => $this->description,
        ];
    }
}
