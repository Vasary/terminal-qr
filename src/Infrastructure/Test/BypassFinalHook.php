<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use Zfekete\BypassReadonly\BypassReadonly;

final class BypassFinalHook implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        BypassReadonly::enable();
    }
}
