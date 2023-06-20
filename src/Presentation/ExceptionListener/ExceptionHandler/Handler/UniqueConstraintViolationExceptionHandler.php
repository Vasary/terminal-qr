<?php

declare(strict_types = 1);

namespace App\Presentation\ExceptionListener\ExceptionHandler\Handler;

use App\Infrastructure\HTTP\ErrorResponse;
use App\Infrastructure\HTTP\JsonResponse;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Throwable;

final class UniqueConstraintViolationExceptionHandler extends AbstractHandler
{
    private const CODE = 409;
    private const MESSAGE = 'Unique constraint violation';

    public function handle(Throwable $exception): JsonResponse
    {
        $this->couldBeHandle($exception);

        return new ErrorResponse(
            [
                'code' => self::CODE,
                'message' => self::MESSAGE,
                'debug' => $exception->getMessage(),
            ],
            self::CODE
        );
    }

    protected function getType(): string
    {
        return UniqueConstraintViolationException::class;
    }
}
