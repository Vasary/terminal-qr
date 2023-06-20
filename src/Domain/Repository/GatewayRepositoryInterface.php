<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Id;

interface GatewayRepositoryInterface
{
    public function findById(Id $id): ?Gateway;

    public function create(
        string $title,
        string $callback,
        string $host,
        string $portal,
        string $currency,
        string $key,
    ): Gateway;
}
