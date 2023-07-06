<?php

declare(strict_types = 1);

namespace App\Infrastructure\Assert;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class IdCollectionValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IdCollection) {
            throw new UnexpectedTypeException($constraint, IdCollection::class);
        }

        if (!$constraint->required && empty($value)) {
            return;
        }

        if (true === $constraint->required && empty($value)) {
            $this->context->buildViolation($constraint->requiredMessage)->addViolation();

            return;
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        if (0 === count($value)) {
            $this->context->buildViolation($constraint->emptyMessage)->addViolation();

            return;
        }

        $context = $this->context;
        foreach ($value as $key => $unitId) {
            $this
                ->context
                ->getValidator()
                ->inContext($context)
                ->atPath('['.$key.']')
                ->validate($unitId, new Uuid());
        }
    }
}
