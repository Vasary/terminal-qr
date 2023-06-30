<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Model\Store;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\Id;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Generator;

interface StoreRepositoryInterface
{
    public function create(string $code, string $title, string $description): Store;

    public function find(): Generator;

    public function findOne(Id $id): ?Store;

    public function findByCriteria(array $searchFields, array $orderBy, int $page, int $size): Paginator;

    public function update(Store $store): void;

    public function delete(Store $store): void;

    public function findByCode(Code $code): ?Store;
}
