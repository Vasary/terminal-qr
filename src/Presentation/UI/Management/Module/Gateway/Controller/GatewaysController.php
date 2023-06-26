<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Controller;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Module\Gateway\Controller\Request\GatewaysRequest;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\OrderByField;
use App\Shared\Transfer\SearchCriteria;
use App\Shared\Transfer\SearchField;

#[Route(path: '/management/gateways', name: 'management_gateways', methods: ['GET'])]
final class GatewaysController extends AbstractController
{
    private const PAGE_LIMIT = 25;

    public function __construct(private readonly GatewayFacadeInterface $gatewayFacade)
    {
    }

    private function getSearchRequest(GatewaysRequest $request): array
    {
        if (null === $request->searchValue) {
            return [];
        }

        return array_map(
            fn (string $field) => new SearchField($field, $request->searchValue),
            $request->searchFields
        );
    }

    private function getSortRequest(GatewaysRequest $request): array
    {
        if (null === $request->orderBy) {
            return [];
        }

        [$orderByField, $orderByDirection] = explode(':', $request->orderBy);

        return [new OrderByField($orderByField, $orderByDirection)];
    }

    public function __invoke(GatewaysRequest $request): HTMLResponse
    {
        // TODO Full refactoring
        $this->isAccessGranted();

        $search = $this->getSearchRequest($request);
        $orderBy = $this->getSortRequest($request);

        $page = null === $request->page
            ? 1
            : (int) $request->page;

        $current = [];

        $current['order'] = count($orderBy) > 0 ? [
                'field' => $orderBy[0]->field(),
                'direction' => $orderBy[0]->direction(),
            ] : [
                'field' => '',
                'direction' => '',
            ];

        $view = $this->renderTemplate('@gateway/gateways.html.twig', [
            'items' => $this->gatewayFacade->findByCriteria(
                new SearchCriteria($search, $orderBy, $page, self::PAGE_LIMIT)
            ),
            'page' => $request->page,
            'order' => $request->orderBy,
            'searchValue' => $request->searchValue,
            'searchFields' => $request->searchFields,
            'current' => $current,
            'config' => [
                'searchFields' => ['title', 'host', 'portal'],
            ],
        ]);

        return new HTMLResponse($view);
    }
}
