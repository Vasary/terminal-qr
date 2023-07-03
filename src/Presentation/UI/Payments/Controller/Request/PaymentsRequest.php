<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Payments\Controller\Request;

use App\Infrastructure\HTTP\AbstractRequest;

final class PaymentsRequest extends AbstractRequest
{
    public ?string $searchValue = null;

    /**
     * @var string[]
     */
    public array $searchFields = [];

    public ?string $orderBy = null;

    public ?string $page;
}
