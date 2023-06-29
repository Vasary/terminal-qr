<?php

declare(strict_types=1);

namespace App\Domain\Enum;

use Stringable;

enum PaymentStatusEnum: int
{
    case Init = 0;
    case Token = 1;
    case Registered = 2;
    case Processing = 3;
    case SUCCESSFULLY = 4;
    case FAILURE = 5;

    public function status(): string
    {
        return $this->name;
    }
}
