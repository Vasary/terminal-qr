<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Stores\Controller\Request;

use App\Infrastructure\HTTP\AbstractRequest;

final class StoresRequest extends AbstractRequest
{
    public ?string $searchValue = null;

    /**
     * @var string[]
     */
    public array $searchFields = [];

    public ?string $orderBy = null;

    public ?string $page;
}
