<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Terminal\Controller;

use App\Application\Store\Business\StoreFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Response\HTMLResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/management/terminals', name: 'management_terminals', methods: ['GET'])]
final class ListController extends AbstractController
{
    public function __construct(private readonly StoreFacadeInterface $storeFacade)
    {
    }

    public function __invoke(Request $request): Response
    {
        $this->isAccessGranted();

        $view = $this->renderTemplate('@terminals/terminals.html.twig', [
            'stores' => $this->storeFacade->find(),
        ]);

        return new HTMLResponse($view);
    }
}