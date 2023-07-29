<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Stores\Controller;

use App\Application\Store\Business\StoreFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Module\Stores\Controller\Request\StoresRequest;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\SearchCriteria;

#[Route(path: '/management/stores', name: 'management_stores', methods: ['GET'])]
final class StoresController extends AbstractController
{
    private const PAGE_LIMIT = 25;

    public function __construct(private readonly StoreFacadeInterface $storeFacade)
    {
    }

    public function __invoke(StoresRequest $request): HTMLResponse
    {
        $this->isAccessGranted();

        $search = $this->getSearchRequest($request);
        $orderBy = $this->getSortRequest($request);
        $page = $this->getPage($request);
        $current = $this->getCurrent($request);

        $view = $this->renderTemplate('@stores/stores.html.twig', [
            'stores' => $this->storeFacade->findByCriteria(
                new SearchCriteria($search, $orderBy, $page, self::PAGE_LIMIT)
            ),
            'page' => $request->page,
            'order' => $request->orderBy,
            'searchValue' => $request->searchValue,
            'searchFields' => $request->searchFields,
            'current' => $current,
            'config' => [
                'searchFields' => ['title', 'code']
            ]
        ]);

        return new HTMLResponse($view);
    }
}
