<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Form;

use App\Infrastructure\Assert\Length;
use App\Infrastructure\Assert\NotBlank;
use App\Infrastructure\Form\Data\PropertyAccessorTrait;

final class Credential
{
    use PropertyAccessorTrait;

    #[NotBlank]
    private ?string $key = null;

    #[Length(min: 1, max: 255)]
    #[NotBlank]
    private ?string $value = null;

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }

    public function key(): ?string
    {
        return $this->key;
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function withKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function withValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
}
