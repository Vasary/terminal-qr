<?php

declare(strict_types = 1);

namespace App\Infrastructure\DateTime;

use Carbon\CarbonImmutable;
use DateTimeImmutable;

if (!function_exists('now')) {
    function now(): DateTimeImmutable
    {
        return CarbonImmutable::now()->toImmutable();
    }
}
