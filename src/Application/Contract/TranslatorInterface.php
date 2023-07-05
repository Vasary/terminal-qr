<?php

declare(strict_types=1);

namespace App\Application\Contract;

interface TranslatorInterface
{
    public function trans(string $id): string;
}
