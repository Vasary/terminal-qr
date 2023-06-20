<?php

declare(strict_types = 1);

namespace App\Domain\Factory\UUID;

use App\Domain\ValueObject\Uuid;

interface IdFactoryInterface
{
    public function v4(): Uuid;

    public function fromString(string $uuidString): Uuid;
}
