<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Stores\Controller;

use App\Application\Contract\TranslatorInterface;
use App\Application\Store\Business\StoreFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse as BaseResponse;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Management\Module\Stores\Form\DeleteType;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\StoreDelete;

#[Route(path: '/management/store/delete/{id}', name: 'management_store_delete', methods: ['GET', 'POST'])]
final class DeleteController extends AbstractController
{
    public function __construct(private readonly StoreFacadeInterface $storeFacade, private readonly TranslatorInterface $translator)
    {
    }

    public function __invoke(HttpRequest $requestStack): BaseResponse
    {
        $this->isAccessGranted();

        $request = $requestStack->getRequest();
        $form = $this->createForm(DeleteType::class);

        $form->get('id')->setData($request->get('id'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('cancel')->isClicked()) {
                return $this->redirectTo('management_stores');
            }

            $this->storeFacade->delete(StoreDelete::fromArray([
                'id' => $form->get('id')->getData(),
            ]));

            return $this->redirectTo('management_stores');
        }

        $view = $this->renderTemplate('@management/form-delete.html.twig', [
            'form' => $form,
            'title' => sprintf('%s: %s', $this->translator->trans('stores.page.title.delete'), $request->get('id')),
        ]);

        return new HTMLResponse($view);
    }
}
