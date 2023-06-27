<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Controller;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Gateway\Form\CreateData;
use App\Presentation\UI\Management\Module\Gateway\Form\CreateType;
use App\Presentation\UI\Management\Module\Gateway\Form\Data;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\GatewayCreate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/management/gateway', name: 'management_getaway_create', methods: ['GET', 'POST'])]
final class CreateController extends AbstractController
{
    private const PAGE_TITLE = 'Create new gateway';

    public function __construct(private readonly GatewayFacadeInterface $gatewayFacade)
    {
    }

    public function __invoke(Request $request): Response
    {
        $this->isAccessGranted();

        $data = new Data();
        $form = $this->createForm(CreateType::class, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->gatewayFacade->create(GatewayCreate::fromArray($data->toArray()));

            return $this->redirectToRoute('management_gateways');
        }

        $view = $this->renderTemplate('@management/form.html.twig', [
            'form' => $form,
            'title' => self::PAGE_TITLE,
        ]);

        return new HTMLResponse($view);
    }
}
