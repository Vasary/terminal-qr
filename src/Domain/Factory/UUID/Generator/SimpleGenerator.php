<?php

declare(strict_types = 1);

namespace App\Domain\Factory\UUID\Generator;

use Ramsey\Uuid\Uuid;

final class SimpleGenerator implements GeneratorInterface
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
