<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Controller;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse as BaseResponse;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Management\Module\Gateway\Form\CreateType;
use App\Presentation\UI\Management\Module\Gateway\Form\Data;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\GatewayCreate;

#[Route(path: '/management/gateway', name: 'management_getaway_create', methods: ['GET', 'POST'])]
final class CreateController extends AbstractController
{
    private const PAGE_TITLE = 'gateways.form.title';

    public function __construct(private readonly GatewayFacadeInterface $gatewayFacade)
    {
    }

    public function __invoke(HttpRequest $request): BaseResponse
    {
        $this->isAccessGrantedUnless('ROLE_ADMIN');

        $data = new Data();
        $form = $this->createForm(CreateType::class, $data);

        $form->handleRequest($request->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->gatewayFacade->create(GatewayCreate::fromArray($data->toArray()));

            return $this->redirectTo('management_gateways');
        }

        $view = $this->renderTemplate('@management/form.html.twig', [
            'form' => $form,
            'title' => self::PAGE_TITLE,
        ]);

        return new HTMLResponse($view);
    }
}
