<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Context;

interface ModelContextInterface
{
    public function __invoke(): object;
}
