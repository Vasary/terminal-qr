<?php

declare(strict_types=1);


namespace App\Application\Payment\Business;

use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\SearchCriteria;

interface PaymentFacadeInterface
{
    public function findByCriteria(SearchCriteria $criteria): Pagination;
}
