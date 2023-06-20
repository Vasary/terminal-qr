<?php

declare(strict_types = 1);

namespace App\Presentation\ExceptionListener\ExceptionHandler\Handler;

use App\Infrastructure\HTTP\JsonResponse;
use RuntimeException;
use Throwable;

abstract class AbstractHandler
{
    public function __construct()
    {
    }

    abstract protected function handle(Throwable $exception): JsonResponse;

    abstract protected function getType(): string;

    protected function couldBeHandle(Throwable $exception): void
    {
        $instance = $this->getType();

        if (!($exception instanceof $instance)) {
            throw new RuntimeException();
        }
    }
}
