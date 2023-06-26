<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Controller;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Module\Gateway\Form\DeleteType;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\GatewayDelete;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/management/gateway/delete/{id}', name: 'management_gateway_delete', methods: ['GET', 'POST'])]
final class DeleteController extends AbstractController
{
    public function __construct(private readonly GatewayFacadeInterface $gatewayFacade)
    {
    }

    public function __invoke(Request $request): Response
    {
        $this->isAccessGranted();

        $form = $this->createForm(DeleteType::class);

        $form->get('id')->setData($request->get('id'));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('management_gateways');
            }

            $this->gatewayFacade->delete(GatewayDelete::fromArray([
                'id' => $form->get('id')->getData(),
            ]));

            return $this->redirectToRoute('management_gateways');
        }

        $view = $this->renderTemplate('@management/form-delete.html.twig', [
            'form' => $form,
            'title' => 'Delete gateway ' . $request->get('id'),
        ]);

        return new HTMLResponse($view);
    }
}
