<?php

declare(strict_types = 1);

namespace App\Presentation\ExceptionListener\ExceptionHandler\Handler;

use App\Infrastructure\HTTP\ErrorResponse;
use App\Infrastructure\HTTP\Exception\ValidationException;
use App\Infrastructure\HTTP\JsonResponse;
use Throwable;

final class ValidationExceptionHandler extends AbstractHandler
{
    private const CODE = 400;

    public function handle(Throwable $exception): JsonResponse
    {
        $this->couldBeHandle($exception);

        return new ErrorResponse(
            [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'constraints' => $exception->getConstrains(),
            ],
            self::CODE
        );
    }

    protected function getType(): string
    {
        return ValidationException::class;
    }
}
