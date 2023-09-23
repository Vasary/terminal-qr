<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Stores\Form;

use App\Infrastructure\Assert\IdCollection;
use App\Infrastructure\Assert\Length;
use App\Infrastructure\Assert\NotBlank;
use App\Infrastructure\Form\Data\PropertyAccessorTrait;

final class Data
{
    use PropertyAccessorTrait;

    #[Length(min: 1, max: 50)]
    #[NotBlank]
    private string $title;

    /** @var string[] */
    #[IdCollection(true)]
    #[NotBlank]
    private array $gateways;

    #[NotBlank]
    private string $description;

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'gateways' => $this->gateways,
            'description' => $this->description,
        ];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function withTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function gateways(): array
    {
        return $this->gateways;
    }

    public function withGateways(array $gateways): self
    {
        $this->gateways = $gateways;
        return $this;
    }

    public function addGateway(string $id): self
    {
        $this->gateways[] = $id;
        return $this;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function withDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
