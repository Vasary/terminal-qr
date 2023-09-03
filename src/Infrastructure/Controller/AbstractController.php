<?php

declare(strict_types = 1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\HTTP\AbstractRequest;
use App\Infrastructure\HTTP\RedirectResponse;
use App\Infrastructure\HTTP\SearchAndOrderRequest;
use App\Shared\Transfer\OrderByField;
use App\Shared\Transfer\SearchField;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyController;

abstract class AbstractController extends SymfonyController
{
    private const AUTHENTICATED_FULLY = 'IS_AUTHENTICATED_FULLY';

    protected function renderTemplate(string $view, array $parameters = []): string
    {
        return $this->renderView($view, $parameters);
    }

    protected function isAccessGranted(): void
    {
        $this->denyAccessUnlessGranted(self::AUTHENTICATED_FULLY);
    }

    protected function isAccessGrantedUnless(string $role): void
    {
        $this->denyAccessUnlessGranted($role);
    }

    protected function getSearchRequest(AbstractRequest $request): array
    {
        if (null === $request->searchValue) {
            return [];
        }

        return array_map(
            fn (string $field) => new SearchField($field, $request->searchValue),
            $request->searchFields
        );
    }

    protected function getSortRequest(AbstractRequest $request): array
    {
        if (null === $request->orderBy) {
            return [];
        }

        [$orderByField, $orderByDirection] = explode(':', $request->orderBy);

        return [new OrderByField($orderByField, $orderByDirection)];
    }

    protected function redirectTo(string $route, array $parameters = []): RedirectResponse
    {
        return new RedirectResponse($this->generateUrl($route, $parameters), 302);
    }

    protected function getPage(SearchAndOrderRequest $request): int
    {
        return null === $request->page
            ? 1
            : (int) $request->page;
    }

    protected function getCurrent(SearchAndOrderRequest $request): array
    {
        $orderBy = $this->getSortRequest($request);

        $current['order'] = count($orderBy) > 0 ? [
            'field' => $orderBy[0]->field(),
            'direction' => $orderBy[0]->direction(),
        ] : [
            'field' => '',
            'direction' => '',
        ];

        return $current;
    }
}
