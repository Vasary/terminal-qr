<?php

declare(strict_types = 1);

namespace App\Presentation\HealthCheck\Response;

use App\Application\HealthCheck\Business\Checker\Response;
use App\Infrastructure\HTTP\JsonResponse;
use App\Infrastructure\Serializer\Serializer;

final class HealthCheckResponse extends JsonResponse
{
    public function __construct(array $checks)
    {
        parent::__construct($this->build($checks), $this->resolveStatusCode($checks));
    }

    private function build(array $responses): array
    {
        $serializer = Serializer::create();

        return array_map(
            fn(Response $response) => $serializer->toArray($response),
            $responses
        );
    }

    private function resolveStatusCode(array $checks): int
    {
        $statusCode = 200;

        foreach ($checks as $check) {
            /** @var Response $check */
            if (false === $check->result()) {
                return 500;
            }
        }

        return $statusCode;
    }
}
