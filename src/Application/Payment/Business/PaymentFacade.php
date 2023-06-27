<?php

declare(strict_types=1);

namespace App\Application\Payment\Business;

use App\Application\Payment\Business\Reader\PaymentReader;
use App\Application\Payment\Business\Writer\PaymentWriter;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\SearchCriteria;
use Generator;

final readonly class PaymentFacade implements PaymentFacadeInterface
{
    public function __construct(
        private PaymentReader $reader,
        private PaymentWriter $writer,
    )
    {
    }

    public function findByCriteria(SearchCriteria $criteria): Pagination
    {
        return $this->reader->findByCriteria($criteria);
    }

    public function findById(Id $id)
    {
        return $this->reader->findOneById($id);
    }
}
