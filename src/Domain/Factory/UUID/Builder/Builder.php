<?php

declare(strict_types = 1);

namespace App\Domain\Factory\UUID\Builder;

use App\Domain\ValueObject\Uuid;

final class Builder implements BuilderInterface
{
    public function build(string $bytes): Uuid
    {
        return new Uuid($bytes);
    }
}
