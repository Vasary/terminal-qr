<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Stores\StoresController;

use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Stores\Controller\Response\StoresResponse;
use App\Presentation\UI\Management\Stores\Form\CreateType;
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

        $form = $this->createForm(CreateType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $store = $this->storeFacade->create(StoreCreate::fromArray([
                    'code' => $form->get('code')->getData(),
                    'title' => $form->get('title')->getData(),
                    'description' => $form->get('description')->getData(),
                ]));

            foreach ($form->get('gateways')->getData() as $terminal) {
                $this->storeFacade->addGateway($store->id(), Id::fromString($terminal));
            }

            return $this->redirectToRoute('management_stores');
        }

        $view = $this->renderTemplate('@stores/form.html.twig', [
            'form' => $form,
            'title' => 'Create new store',
        ]);

        return new StoresResponse($view);
    }
}
