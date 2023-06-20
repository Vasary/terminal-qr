<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use IteratorAggregate;

final readonly class Pagination
{
    public function __construct(private int $totalItems, private int $totalPages, private IteratorAggregate $aggregate)
    {
    }

    public function items(): int
    {
        return $this->totalItems;
    }

    public function pages(): int
    {
        return $this->totalPages;
    }

    public function aggregate(): IteratorAggregate
    {
        return $this->aggregate;
    }
}
