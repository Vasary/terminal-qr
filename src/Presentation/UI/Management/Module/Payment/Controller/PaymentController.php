<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Payment\Controller;

use App\Application\Payment\Business\PaymentFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Management\Module\Payment\Form\PaymentData;
use App\Presentation\UI\Management\Module\Payment\Form\PaymentType;
use App\Presentation\UI\Management\Response\HTMLResponse;

#[Route(path: '/management/payment/{id}', name: 'management_payment', methods: ['GET'])]
final class PaymentController extends AbstractController
{
    public function __construct(private readonly PaymentFacadeInterface $facade)
    {
    }

    public function __invoke(HttpRequest $requestStack): HTMLResponse
    {
        $this->isAccessGrantedUnless('ROLE_MANAGER');

        $request = $requestStack->getRequest();

        $payment = $this->facade->findById(Id::fromString($request->get('id')));

        $view = $this->renderTemplate('@payment/payment.html.twig', [
            'payment' => $this->facade->findById(Id::fromString($request->get('id'))),
            'paymentData' => $this->createForm(PaymentType::class, new PaymentData($payment)),
        ]);

        return new HTMLResponse($view);
    }
}
