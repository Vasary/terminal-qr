<?php

declare(strict_types = 1);

namespace App\Application\Store\Business\Reader;

enum OrderByField: string
{
    case Title = 'title';
    case Code = 'code';
    case Description = 'description';
    case CreatedAt = 'createdAt';
    case UpdatedAt = 'updatedAt';
}
