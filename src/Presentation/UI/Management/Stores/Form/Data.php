<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Stores\Form;

use Symfony\Component\Validator\Constraints as Assert;

final class Data
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 50)]
    public string $title;

    #[Assert\Collection([
        new Assert\Uuid()
    ])]
    #[Assert\NotBlank]
    public array $gateways;

    #[Assert\NotBlank]
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
