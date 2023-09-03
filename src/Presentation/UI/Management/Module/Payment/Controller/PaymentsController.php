<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Payment\Controller;

use App\Application\Payment\Business\PaymentFacadeInterface;
use App\Application\Security\SecurityInterface;
use App\Application\Store\Business\StoreFacadeInterface;
use App\Application\User\Business\UserFacadeInterface;
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

    public function __construct(
        private readonly PaymentFacadeInterface $facade,
        private readonly StoreFacadeInterface $storeFacade,
        private readonly UserFacadeInterface $userFacade,
        private readonly SecurityInterface $security,
    )
    {
    }

    private function getStores(): array
    {
        $user = $this->userFacade->findById($this->security->getDomainUser()->id());

        if (in_array('ROLE_MANAGER', $user->roles())) {
            return $user->stores()->toArray();
        }

        if (in_array('ROLE_ADMIN', $user->roles())) {
            return iterator_to_array($this->storeFacade->find());
        }

        return [];
    }

    public function __invoke(PaymentsRequest $request): HTMLResponse
    {
        $this->isAccessGrantedUnless('ROLE_MANAGER');

        $search = $this->getSearchRequest($request);
        $orderBy = $this->getSortRequest($request);
        $page = $this->getPage($request);
        $current = $this->getCurrent($request);
        $stores = $this->getStores();

        $view = $this->renderTemplate('@payment/payments.html.twig', [
            'items' => $this->facade->findByCriteria(
                new SearchCriteria($search, $orderBy, $page, self::PAGE_LIMIT, array_map(
                    fn (Store $store) => (string) $store->id(), $stores
                ))
            ),
            'page' => $request->page,
            'order' => $request->orderBy,
            'searchValue' => $request->searchValue,
            'current' => $current,
            'config' => $this->getViewConfig(),
            'userStores' => $stores,
        ]);

        return new HTMLResponse($view);
    }
}
