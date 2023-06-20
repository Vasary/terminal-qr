<?php

declare(strict_types = 1);

namespace App\Application\HealthCheck\Business\Checker;

interface HealthCheckerPluginInterface
{
    public function check(): Response;
}
