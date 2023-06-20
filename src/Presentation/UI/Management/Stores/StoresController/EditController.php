<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Stores\StoresController;

use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Stores\Controller\Response\StoresResponse;
use App\Presentation\UI\Management\Stores\Form\CreateType;
use App\Presentation\UI\Management\Stores\Form\UpdateType;
use App\Shared\Transfer\StoreUpdate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/management/store/edit/{id}', name: 'management_store_edit', methods: ['GET', 'POST'])]
final class EditController extends AbstractController
{
    public function __construct(private readonly StoreFacadeInterface $storeFacade)
    {
    }

    public function __invoke(Request $request): Response
    {
        $this->isAccessGranted();

        $form = $this->createForm(UpdateType::class);
        $id = $request->get('id');

        $store = $this->storeFacade->findById(Id::fromString($id));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $store = $this->storeFacade->update(StoreUpdate::fromArray([
                'code' => $form->get('code')->getData(),
                'title' => $form->get('title')->getData(),
                'description' => $form->get('description')->getData(),
                'id' => $id,
            ]));

            foreach ($store->gateway() as $gateway) {
                $store->removeGateway($gateway);
            }

            foreach ($form->get('gateways')->getData() as $terminal) {
                $this->storeFacade->addGateway($store->id(), Id::fromString($terminal));
            }

            return $this->redirectToRoute('management_stores');
        }

        $form->get('title')->setData((string) $store->title());
        $form->get('code')->setData((string) $store->code());
        $form->get('description')->setData((string) $store->description());

        $terminals = [];
        foreach ($store->gateway() as $gateway) {
            $terminals[] = (string) $gateway->id();
        }

        $form->get('gateways')->setData($terminals);

        $view = $this->renderTemplate('@stores/form.html.twig', [
            'form' => $form,
            'title' => 'Update store: ' . $store->title(),
        ]);

        return new StoresResponse($view);
    }
}
