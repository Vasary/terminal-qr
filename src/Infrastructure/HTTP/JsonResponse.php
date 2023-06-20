<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP;

use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;

abstract class JsonResponse extends SymfonyJsonResponse
{
}
