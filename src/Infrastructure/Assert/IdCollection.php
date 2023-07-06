<?php

declare(strict_types = 1);

namespace App\Infrastructure\Assert;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class IdCollection extends Constraint
{
    public string $requiredMessage = 'Нужно выбраьть хотябы один элемент из списка';
    public string $emptyMessage = 'Список не может быть пустым';
    public bool $required;

    #[HasNamedArguments]
    public function __construct(bool $mode = false, public bool $validateId = false, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->required = $mode;
    }

    public function validatedBy(): string
    {
        return self::class . 'Validator';
    }
}
