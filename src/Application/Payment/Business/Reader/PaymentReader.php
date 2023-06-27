<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\Reader;

use App\Domain\Model\Payment;
use App\Domain\Repository\PaymentRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\SearchCriteria;

final class PaymentReader
{
    public function __construct(private readonly PaymentRepositoryInterface $repository)
    {
    }

    public function findByCriteria(SearchCriteria $criteria): Pagination
    {
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

    public function findOneById(Id $id): Payment
    {
        return $this->repository->findOne($id);
    }
}
