<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Id;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Generator;

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

    public function find(): Generator;

    public function update(Gateway $gateway): void;

    public function findByCriteria(array $fields, array $orderBy, int $page, int $limit): Paginator;

    public function delete(Gateway $gateway): void;
}
