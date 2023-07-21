<?php

declare(strict_types = 1);

namespace App\Domain\Enum;

enum WorkflowTransitionEnum: string
{
    case tokenized = 'tokenized';
    case register = 'register';
    case wait = 'wait';
    case complete = 'complete';
    case failure = 'failure';
    case timeout = 'timeout';
    case unexpected = 'unexpected';
    case expire = 'expire';
    case alert = 'alert';
}
