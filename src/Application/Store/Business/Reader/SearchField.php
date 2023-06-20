<?php

declare(strict_types = 1);

namespace App\Application\Store\Business\Reader;

enum SearchField: string
{
    case Title = 'title';
    case Code = 'code';
    case Description = 'description';
}
