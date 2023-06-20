<?php

declare(strict_types = 1);

namespace App\Infrastructure\Assert;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class StringAttributeValidator extends ConstraintValidator
{
    /**
     * @param StringAttribute $constraint
     */
    public function validate(mixed $value, mixed $constraint): void
    {
        if (!is_subclass_of($constraint, StringAttribute::class)) {
            throw new UnexpectedTypeException($constraint, StringAttribute::class);
        }

        if (!$constraint->required && empty($value)) {
            return;
        }

        if (true === $constraint->required && empty($value)) {
            $this
                ->context
                ->buildViolation($constraint->requiredMessage)
                ->setParameter('{name}', $constraint->name)
                ->addViolation();
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (mb_strlen($value) > $constraint->maxLength || mb_strlen($value) < $constraint->minLength) {
            $this
                ->context
                ->buildViolation($constraint->stringLength)
                ->setParameter('{name}', $constraint->name)
                ->setParameter('{min}', (string) $constraint->minLength)
                ->setParameter('{max}', (string) $constraint->maxLength)
                ->addViolation();
        }
    }
}
