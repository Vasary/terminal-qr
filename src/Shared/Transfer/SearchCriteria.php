<?php

declare(strict_types = 1);

namespace App\Shared\Transfer;

final class SearchCriteria
{
    use CreateFromTrait;

    public function __construct
    (
        /** @var SearchField[] */
        private readonly array $fields,
        /** @var OrderByField[] */
        private readonly array $orderBy,
        private readonly int $page,
        private readonly int $limit,
    )
    {
    }

    public function fields(): array
    {
        return $this->fields;
    }

    public function orderBy(): array
    {
        return $this->orderBy;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function limit(): int
    {
        return $this->limit;
    }
}
