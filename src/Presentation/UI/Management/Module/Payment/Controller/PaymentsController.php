<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Payment\Controller;

use App\Application\Payment\Business\PaymentFacadeInterface;
use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\Model\Store;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\_config\PaymentViewConfigTrait;
use App\Presentation\UI\Management\Module\Payment\Controller\Request\PaymentsRequest;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\SearchCriteria;

#[Route(path: '/management/payments', name: 'management_payments', methods: ['GET'])]
final class PaymentsController extends AbstractController
{
    use PaymentViewConfigTrait;

    private const PAGE_LIMIT = 25;

    public function __construct(private readonly PaymentFacadeInterface $facade, private readonly StoreFacadeInterface $storeFacade)
    {
    }

    public function __invoke(PaymentsRequest $request): HTMLResponse
    {
        $this->isAccessGranted();

        $search = $this->getSearchRequest($request);
        $orderBy = $this->getSortRequest($request);
        $page = $this->getPage($request);
        $current = $this->getCurrent($request);

        $stores = array_map(
            fn(Store $store) => (string) $store->id(),
            iterator_to_array($this->storeFacade->find())
        );

        $view = $this->renderTemplate('@payment/payments.html.twig', [
            'items' => $this->facade->findByCriteria(
                new SearchCriteria($search, $orderBy, $page, self::PAGE_LIMIT, $stores)
            ),
            'page' => $request->page,
            'order' => $request->orderBy,
            'searchValue' => $request->searchValue,
            'current' => $current,
            'config' => $this->getViewConfig(),
        ]);

        return new HTMLResponse($view);
    }
}
