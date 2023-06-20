<?php

declare(strict_types = 1);

namespace App\Domain\Factory\UUID\Builder;

use App\Domain\ValueObject\Uuid;

interface BuilderInterface
{
    public function build(string $bytes): Uuid;
}
