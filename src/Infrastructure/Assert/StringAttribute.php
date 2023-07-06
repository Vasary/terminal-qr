<?php

declare(strict_types = 1);

namespace App\Infrastructure\Assert;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class StringAttribute extends Constraint
{
    public string $requiredMessage = 'Поля является обязательным.';
    public string $stringLength = 'Аттрибут {name} должен быть больше чем {min} и меньше чем {max}.';

    public function __construct(
        public readonly bool $required,
        public readonly int $minLength,
        public readonly int $maxLength,
        public readonly string $name,
    )
    {
        parent::__construct();
    }

    public function validatedBy(): string
    {
        return self::class . 'Validator';
    }
}
