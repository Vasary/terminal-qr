<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test;

use PHPUnit\Runner\BeforeTestHook;
use Zfekete\BypassReadonly\BypassReadonly;

final class BypassReadonlyHook implements BeforeTestHook
{
    public function executeBeforeTest(string $test): void
    {
        BypassReadonly::enable();
    }
}
