<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Terminal\Controller;

use App\Application\Payment\Business\PaymentFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Terminal\Response\TerminalResponse;

#[Route(path: '/terminal/status/{paymentId}', name: 'terminal_status')]
final class TerminalStatusController extends AbstractController
{
    public function __construct(private readonly PaymentFacadeInterface $paymentFacade,)
    {
    }

    public function __invoke(HttpRequest $requestStack): TerminalResponse
    {
        $errors = [];
        $payment = $this->paymentFacade->findById(Id::fromString($requestStack->getRequest()->get('paymentId')));
        $terminal = $payment->store()->code() . ':' . $payment->gateway()->key();

        $view = $this->renderTemplate('@terminal/status.html.twig', [
            'errors' => $errors,
            'payment' => $payment,
            'terminal' => $terminal,
        ]);

        return new TerminalResponse($view);
    }
}
