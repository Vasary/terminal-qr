<?php

declare(strict_types = 1);

namespace App\Application\Gateway\Business\Reader;

use App\Domain\Model\Gateway;
use App\Domain\Repository\GatewayRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\SearchCriteria;
use Generator;

final readonly class GatewayReader
{
    public function __construct(private GatewayRepositoryInterface $repository)
    {
    }

    public function findById(Id $id): ?Gateway
    {
        return $this->repository->findById($id);
    }

    public function all(): Generator
    {
        return $this->repository->find();
    }

    public function findByCriteria(SearchCriteria $criteria): Pagination
    {
        // validate

        $paginator = $this->repository->findByCriteria(
            $criteria->fields(),
            $criteria->orderBy(),
            $criteria->page(),
            $criteria->limit()
        );

        $items = count($paginator);
        $pages = (int) ceil($items / $criteria->limit());

        return new Pagination($items, $pages, $paginator);
    }
}
