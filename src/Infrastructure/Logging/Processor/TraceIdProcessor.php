<?php

declare(strict_types = 1);

namespace App\Infrastructure\Logging\Processor;

use Monolog\LogRecord;

final class TraceIdProcessor
{
    public function __invoke(array|LogRecord $record): array|LogRecord
    {
        $record['extra']['x-trace-id'] = 'x-trace-id';

        return $record;
    }
}
