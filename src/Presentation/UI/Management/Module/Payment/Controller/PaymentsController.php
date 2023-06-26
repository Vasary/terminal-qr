<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Payment\Controller;

use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Module\Stores\Controller\Request\StoresRequest;
use App\Presentation\UI\Management\Response\HTMLResponse;

#[Route(path: '/management/payments', name: 'management_payments', methods: ['GET'])]
final class PaymentsController extends AbstractController
{
    private const PAGE_LIMIT = 25;

    public function __construct()
    {
    }

    public function __invoke(StoresRequest $request): HTMLResponse
    {
        dd('stop');
    }
}
