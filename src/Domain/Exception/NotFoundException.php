<?php

declare(strict_types = 1);

namespace App\Domain\Exception;

use App\Domain\ValueObject\Id;
use Exception;

final class NotFoundException extends Exception
{
    private const ERROR_CODE = 1;

    public function __construct(string $type, Id $id)
    {
        parent::__construct(
            'Entity with ' . $id . ' of ' . $type . ' not found ',
            self::ERROR_CODE
        );
    }
}
