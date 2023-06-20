<?php

declare(strict_types = 1);

namespace App\Application\Store\Business\Reader;

use App\Domain\Model\Store;
use App\Domain\Repository\StoreRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\SearchCriteria;
use Generator;

final readonly class StoreReader
{
    public function __construct(private StoreRepositoryInterface $repository, private SearchCriteriaValidator $builder,)
    {
    }

    public function all(): Generator
    {
        return $this->repository->find();
    }

    public function findByCriteria(SearchCriteria $criteria): Pagination
    {
        $this->builder->validate($criteria);

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

    public function findById(Id $id): ?Store
    {
        return $this->repository->findOne($id);
    }
}
