<?php

declare(strict_types = 1);

namespace App\Infrastructure\Assert;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class StringAttribute extends Constraint
{
    public string $requiredMessage = 'Attribute {name} is required';
    public string $stringLength = 'Attribute {name} should be more then {min} and less then {max} symbols';

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
