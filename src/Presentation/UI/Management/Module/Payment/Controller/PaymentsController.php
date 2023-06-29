<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Payment\Controller;

use App\Application\Payment\Business\PaymentFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Module\Payment\Controller\Request\PaymentsRequest;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\SearchCriteria;

#[Route(path: '/management/payments', name: 'management_payments', methods: ['GET'])]
final class PaymentsController extends AbstractController
{
    private const PAGE_LIMIT = 25;

    public function __construct(private readonly PaymentFacadeInterface $facade)
    {
    }

    public function __invoke(PaymentsRequest $request): HTMLResponse
    {
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

        $view = $this->renderTemplate('@payment/payments.html.twig', [
            'items' => $this->facade->findByCriteria(
                new SearchCriteria($search, $orderBy, $page, self::PAGE_LIMIT)
            ),
            'page' => $request->page,
            'order' => $request->orderBy,
            'searchValue' => $request->searchValue,
            'searchFields' => $request->searchFields,
            'current' => $current,
            'config' => [
                'searchFields' => ['amount', 'createdAt', 'updatedAt', 'status'],
            ],
        ]);

        return new HTMLResponse($view);
    }
}
