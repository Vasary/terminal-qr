<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Payments\Controller;

use App\Application\Payment\Business\PaymentFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Response\HTMLResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route(path: '/payment/{id}', name: 'payment', methods: ['GET'])]
final class PaymentController extends AbstractController
{
    public function __construct(private readonly PaymentFacadeInterface $facade)
    {
    }

    public function __invoke(Request $request): HTMLResponse
    {
        $this->isAccessGranted();

        $view = $this->renderTemplate('@payments/payment.html.twig', [
            'payment' => $this->facade->findById(Id::fromString($request->get('id'))),
        ]);

        return new HTMLResponse($view);
    }
}
