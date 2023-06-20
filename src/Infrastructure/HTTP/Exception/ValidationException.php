<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP\Exception;

use InvalidArgumentException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationException extends InvalidArgumentException
{
    private const MESSAGE = 'Validation fail';

    public function __construct(private readonly ConstraintViolationListInterface $constraints,) {
        parent::__construct(self::MESSAGE, 400);
    }

    public function getConstrains(): array
    {
        $result = [];

        foreach ($this->constraints as $error) {
            /* @var ConstraintViolation $error */

            $result[] = [
                'property' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        return $result;
    }
}
