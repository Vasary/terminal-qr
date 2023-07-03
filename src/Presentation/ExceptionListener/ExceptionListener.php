<?php

declare(strict_types = 1);

namespace App\Presentation\ExceptionListener;

use App\Presentation\UI\Management\Response\HTMLResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Twig\Environment;

final readonly class ExceptionListener
{
    public function __construct(protected Environment $environment)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
//        $response = new HTMLResponse($this->environment->render('@exception/error.html.twig', [
//            'message' => $event->getThrowable()->getMessage(),
//        ]));
//
//        $event->setResponse($response);
    }
}
