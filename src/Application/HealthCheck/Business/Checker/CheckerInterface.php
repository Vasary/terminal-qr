<?php

declare(strict_types = 1);

namespace App\Application\HealthCheck\Business\Checker;

interface CheckerInterface
{
    public function check(): array;
}
