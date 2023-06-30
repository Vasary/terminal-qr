<?php

declare(strict_types = 1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\HTTP\AbstractRequest;
use App\Presentation\UI\Management\Module\Gateway\Controller\Request\GatewaysRequest;
use App\Shared\Transfer\OrderByField;
use App\Shared\Transfer\SearchField;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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
}
