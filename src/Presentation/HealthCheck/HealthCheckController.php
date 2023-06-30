<?php

declare(strict_types = 1);

namespace App\Presentation\HealthCheck;

use App\Application\HealthCheck\Business\HealthCheckFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\JsonResponse;
use App\Presentation\HealthCheck\Response\HealthCheckResponse;

#[Route('/check', name: 'health', methods: 'GET')]
final class HealthCheckController extends AbstractController
{
    public function __construct(private readonly HealthCheckFacadeInterface $facade)
    {
    }

    public function __invoke(): JsonResponse
    {
        return new HealthCheckResponse($this->facade->check());
    }
}
