<?php

declare(strict_types = 1);

use Carbon\CarbonImmutable;

if (!function_exists('now')) {
    function now(): DateTimeImmutable
    {
        return CarbonImmutable::now()->toImmutable();
    }
}
