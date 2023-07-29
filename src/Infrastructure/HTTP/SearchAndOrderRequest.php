<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP;

abstract class SearchAndOrderRequest extends AbstractRequest
{
    public ?string $searchValue = null;

    /**
     * @var string[]
     */
    public array $searchFields = [];

    public ?string $orderBy = null;

    public ?string $page;
}
