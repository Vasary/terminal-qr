<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Stores\Controller;

use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Module\Stores\Form\CreateType;
use App\Presentation\UI\Management\Module\Stores\Form\Data;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\StoreCreate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/management/store', name: 'management_store_create', methods: ['GET', 'POST'])]
final class CreateController extends AbstractController
{
    public function __construct(private readonly StoreFacadeInterface $storeFacade)
    {
    }

    public function __invoke(Request $request): Response
    {
        $this->isAccessGranted();

        $data = new Data();

        $form = $this->createForm(CreateType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $store = $this->storeFacade->create(StoreCreate::fromArray($data->toArray()));

            foreach ($data->gateways as $gateway) {
                $this->storeFacade->addGateway($store->id(), Id::fromString($gateway));
            }

            return $this->redirectToRoute('management_stores');
        }

        $view = $this->renderTemplate('@management/form.html.twig', [
            'form' => $form,
            'title' => 'Create new store',
        ]);

        return new HTMLResponse($view);
    }
}
