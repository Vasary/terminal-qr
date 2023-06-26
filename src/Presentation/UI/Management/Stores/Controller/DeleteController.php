<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Stores\Controller;

use App\Application\Store\Business\StoreFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Presentation\UI\Management\Stores\Form\DeleteType;
use App\Shared\Transfer\StoreDelete;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/management/store/delete/{id}', name: 'management_store_delete', methods: ['GET', 'POST'])]
final class DeleteController extends AbstractController
{
    public function __construct(private readonly StoreFacadeInterface $storeFacade)
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
                return $this->redirectToRoute('management_stores');
            }

            $this->storeFacade->delete(StoreDelete::fromArray([
                'id' => $form->get('id')->getData(),
            ]));

            return $this->redirectToRoute('management_stores');
        }

        $view = $this->renderTemplate('@management/form-delete.html.twig', [
            'form' => $form,
            'title' => 'Delete store ' . $request->get('id'),
        ]);

        return new HTMLResponse($view);
    }
}
