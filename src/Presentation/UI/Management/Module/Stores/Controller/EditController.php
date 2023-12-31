<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Stores\Controller;

use App\Application\Contract\TranslatorInterface;
use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\Model\Store;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse as BaseResponse;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Management\Module\Stores\Form\Data;
use App\Presentation\UI\Management\Module\Stores\Form\UpdateType;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\StoreUpdate;

#[Route(path: '/management/store/edit/{id}', name: 'management_store_edit', methods: ['GET', 'POST'])]
final class EditController extends AbstractController
{
    public function __construct(private readonly StoreFacadeInterface $storeFacade, private readonly TranslatorInterface $translator)
    {
    }

    private function updateStore(Data $data, Store $store): void
    {
        $store = $this->storeFacade->update(StoreUpdate::fromArray(
            array_merge($data->toArray(), ['id' => (string) $store->id()])
        ));

        foreach ($store->gateway() as $gateway) {
            $store->removeGateway($gateway);
        }

        foreach ($data->gateways() as $gateway) {
            $this->storeFacade->addGateway($store->id(), Id::fromString($gateway));
        }
    }

    public function __invoke(HttpRequest $requestStack): BaseResponse
    {
        $this->isAccessGrantedUnless('ROLE_ADMIN');

        $request = $requestStack->getRequest();
        $store = $this->storeFacade->findById(Id::fromString($request->get('id')));

        $data = new Data();
        $data->withTitle((string) $store->title());
        $data->withDescription((string) $store->description());

        foreach ($store->gateway() as $gateway) {
            $data->addGateway((string) $gateway->id());
        }

        $form = $this->createForm(UpdateType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateStore($data, $store);

            return $this->redirectTo('management_stores');
        }

        $view = $this->renderTemplate('@management/form.html.twig', [
            'form' => $form,
            'title' => $this->translator->trans('stores.page.title.edit') . ': ' . $store->code(),
        ]);

        return new HTMLResponse($view);
    }
}
