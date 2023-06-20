<?php

declare(strict_types = 1);

namespace App\Presentation\API\Store\Create\Request;

use App\Infrastructure\Assert\StringAttribute;
use App\Infrastructure\HTTP\AbstractRequest;

final class CreateRequest extends AbstractRequest
{
    #[StringAttribute(true, 3, 25, 'code')]
    public mixed $code;

    #[StringAttribute(true, 3, 25, 'title')]
    public mixed $title;

    #[StringAttribute(false, 3, 255, 'description')]
    public mixed $description;
}
