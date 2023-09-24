<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Form;

use App\Infrastructure\Assert\Length;
use App\Infrastructure\Assert\NotBlank;
use App\Infrastructure\Form\Data\PropertyAccessorTrait;

/**
 * @method self withKey(string $key)
 * @method self withValue(string $value)
 * @method null|string key()
 * @method null|string value()
 */
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
}
