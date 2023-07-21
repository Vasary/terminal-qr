<?php

declare(strict_types = 1);

namespace App\Domain\Exception;

use Symfony\Component\Security\Core\Exception\UserNotFoundException as Base;

final class WorkflowException extends Base
{
}
