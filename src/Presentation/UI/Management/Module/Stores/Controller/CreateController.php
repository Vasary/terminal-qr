<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Stores\Controller;

use App\Application\Contract\TranslatorInterface;
use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse as BaseResponse;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Management\Module\Stores\Form\CreateType;
use App\Presentation\UI\Management\Module\Stores\Form\Data;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\StoreCreate;

#[Route(path: '/management/store', name: 'management_store_create', methods: ['GET', 'POST'])]
final class CreateController extends AbstractController
{
    public function __construct(private readonly StoreFacadeInterface $storeFacade, private readonly TranslatorInterface $translator)
    {
    }

    public function createStore(Data $data): void
    {
        $store = $this->storeFacade->create(StoreCreate::fromArray($data->toArray()));

        foreach ($data->gateways as $gateway) {
            $this->storeFacade->addGateway($store->id(), Id::fromString($gateway));
        }
    }

    public function __invoke(HttpRequest $requestStack): BaseResponse
    {
        $this->isAccessGranted();

        $data = new Data();

        $form = $this->createForm(CreateType::class, $data);
        $form->handleRequest($requestStack->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->createStore($data);

            return $this->redirectTo('management_stores');
        }

        $view = $this->renderTemplate('@management/form.html.twig', [
            'form' => $form,
            'title' => $this->translator->trans('stores.page.title.create'),
        ]);

        return new HTMLResponse($view);
    }
}
