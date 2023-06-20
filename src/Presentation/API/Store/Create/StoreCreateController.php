<?php

declare(strict_types = 1);

namespace App\Presentation\API\Store\Create;

use App\Application\Store\Business\StoreFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\JsonResponse;
use App\Presentation\API\Store\Create\Request\CreateRequest;
use App\Presentation\API\Store\Create\Response\CreateResponse;
use App\Shared\Transfer\StoreCreate;

#[Route('/store', methods: 'POST')]
final class StoreCreateController extends AbstractController
{
    public function __construct(private readonly StoreFacadeInterface $facade)
    {
    }

    public function __invoke(CreateRequest $request): JsonResponse
    {
        $attributeTransfer = StoreCreate::fromArray($request->toArray());

        return new CreateResponse($this->facade->create($attributeTransfer));
    }
}
