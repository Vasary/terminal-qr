<?php

declare(strict_types = 1);

namespace App\Application\Contract;

interface ContainerInterface
{
    public function has(string $container): bool;

    public function get(string $container): ?object;
}
