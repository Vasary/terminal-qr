<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Terminal\Controller;

use App\Application\Payment\Business\PaymentFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Terminal\Response\PaymentStatusResponse;

#[Route(
    path: '/{id}/json',
    name: 'terminal_status',
    requirements: ['id' => '[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}']
)]
final class TerminalStatusController extends AbstractController
{
    public function __construct(private readonly PaymentFacadeInterface $paymentFacade,)
    {
    }

    public function __invoke(HttpRequest $requestStack): PaymentStatusResponse
    {
        $payment = $this->paymentFacade->findById(Id::fromString($requestStack->getRequest()->get('id')));

        return new PaymentStatusResponse($payment);
    }
}
