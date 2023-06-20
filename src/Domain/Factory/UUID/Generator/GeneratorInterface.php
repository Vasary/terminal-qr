<?php

declare(strict_types = 1);

namespace App\Domain\Factory\UUID\Generator;

interface GeneratorInterface
{
    public function generate(): string;
}
