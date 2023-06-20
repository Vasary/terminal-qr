<?php

declare(strict_types = 1);

namespace App\Presentation\ExceptionListener;

use App\Infrastructure\HTTP\ErrorResponse;
use App\Presentation\ExceptionListener\ExceptionHandler\ChainRunner;
use RuntimeException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

final class ExceptionListener
{
    public function __construct(private readonly ChainRunner $chainRunner)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        try {
            $event->setResponse($this->chainRunner->run($exception));
        } catch (RuntimeException $exception) {
            $event->setResponse($this->createDefaultErrorResponse($exception));
        }
    }

    private function createDefaultErrorResponse(Throwable $exception): ErrorResponse
    {
        return
            new ErrorResponse(
                [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
                500
            );
    }
}
