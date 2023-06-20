<?php

declare(strict_types = 1);

namespace App\Application\HealthCheck\Business;

interface HealthCheckFacadeInterface
{
    public function check(): array;
}
