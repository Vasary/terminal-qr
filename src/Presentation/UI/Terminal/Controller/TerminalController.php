<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Terminal\Controller;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Application\Payment\Business\PaymentFacadeInterface;
use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\Exception\TerminalGeneratingException;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\Key;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse as BaseResponse;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Terminal\Form\CreateType;
use App\Presentation\UI\Terminal\Form\Data;
use App\Presentation\UI\Terminal\Response\TerminalResponse;
use ErrorException;

#[Route(
    path: '/{code}',
    name: 'terminal',
    requirements: ['code' => '^\w+\:[a-z0-9-]+$']
)]
final class TerminalController extends AbstractController
{
    public function __construct(
        private readonly StoreFacadeInterface $storeFacade,
        private readonly GatewayFacadeInterface $gatewayFacade,
        private readonly PaymentFacadeInterface $paymentFacade,
    )
    {
    }

    /**
     * @throws TerminalGeneratingException
     */
    public function __invoke(HttpRequest $requestStack): BaseResponse
    {
        $request = $requestStack->getRequest();
        $code = $request->get('code');

        try {
            [$storeCode, $gatewayKey] = preg_split('/:/ui', $code);
        } catch (ErrorException $exception) {
            throw new TerminalGeneratingException();
        }

        $store = $this->storeFacade->findByCode(new Code($storeCode));
        $gateway = $this->gatewayFacade->findByKey(new Key($gatewayKey));

        if (null === $store || null === $gateway || !$store->gateway()->contains($gateway)) {
            throw new TerminalGeneratingException();
        }

        $data = new Data();
        $data->gateway = (string) $gateway->id();
        $data->store = (string) $store->id();

        $form = $this->createForm(CreateType::class, $data);

        $form->handleRequest($request);
        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $payment = $this->paymentFacade->create($data->amount, $store->id(), $gateway->id());

            return $this->redirectTo('terminal_status_page', ['id' => $payment->id()]);
        }

        $view = $this->renderTemplate('@terminal/terminal.html.twig', [
            'form' => $form,
            'errors' => $errors,
        ]);

        return new TerminalResponse($view);
    }
}
