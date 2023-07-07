<?php

declare(strict_types = 1);

namespace App\Domain\Enum;

use Stringable;

enum PaymentStatusEnum: int
{
    case Init = 0;
    case Token = 1;
    case Registered = 2;
    case Awaiting = 3; // INTERIM_SUCCESS
    case Successfully = 4; // SUCCESS
    case Failure = 5; // FAILED

    case Timeout = 6; // TRANSACTION_EXPIRED

    case Unknown = 1000; // UNKNOWN

    public function status(): string
    {
        return $this->name;
    }
}
