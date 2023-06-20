<?php

declare(strict_types = 1);

namespace App\Application\HealthCheck\Business;

use App\Application\HealthCheck\Business\Checker\CheckerInterface;

readonly final class HealthCheckFacade implements HealthCheckFacadeInterface
{
    public function __construct(private CheckerInterface $checker)
    {
    }

    public function check(): array
    {
        return $this->checker->check();
    }
}
