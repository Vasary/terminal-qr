<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Credentials\Credential;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Key;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Generator;

interface GatewayRepositoryInterface
{
    public function findById(Id $id): ?Gateway;

    public function create(string $title, string $callback, string $key, Credential $credential, Currency $currency): Gateway;

    public function find(): Generator;

    public function update(Gateway $gateway): void;

    public function findByCriteria(array $fields, array $orderBy, int $page, int $limit): Paginator;

    public function delete(Gateway $gateway): void;

    public function findByKey(Key $key): ?Gateway;
}
