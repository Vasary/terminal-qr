<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Form;

use App\Infrastructure\Assert\NotBlank;

final class Credential
{
    #[NotBlank]
    public string $key;

    #[NotBlank]
    public string $value;

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
