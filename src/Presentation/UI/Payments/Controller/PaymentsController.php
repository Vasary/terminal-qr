<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Payments\Controller;

use App\Application\Payment\Business\PaymentFacadeInterface;
use App\Application\User\Business\UserFacadeInterface;
use App\Domain\Model\Store;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\Security\Security;
use App\Presentation\UI\Payments\Controller\Request\PaymentsRequest;
use App\Presentation\UI\Payments\Response\HTMLResponse;
use App\Shared\Transfer\SearchCriteria;

#[Route(path: '/payments', name: 'payments', methods: ['GET'])]
final class PaymentsController extends AbstractController
{
    private const PAGE_LIMIT = 25;

    public function __construct(private readonly PaymentFacadeInterface $facade, private readonly Security $security, private readonly UserFacadeInterface $userFacade)
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

        $stores = [];
        $userStores = $this->userFacade->findById($this->security->getDomainUser()->id())->stores();
        foreach ($userStores as $store) {
            /** @var Store $store */
            $stores[] = (string) $store->id();
        }

        $view = $this->renderTemplate('@payments/payments.html.twig', [
            'items' => $this->facade->findByCriteria(
                new SearchCriteria($search, $orderBy, $page, self::PAGE_LIMIT, $stores)
            ),
            'page' => $request->page,
            'order' => $request->orderBy,
            'searchValue' => $request->searchValue,
            'searchFields' => $request->searchFields,
            'current' => $current,
            'config' => [
                'searchFields' => ['amount', 'createdAt', 'updatedAt', 'status'],
            ],
            'userStores' => $userStores
        ]);

        return new HTMLResponse($view);
    }
}
