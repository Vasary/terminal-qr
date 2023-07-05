<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\Exception\UserNotFoundException as Base;

final class UserNotFoundException extends Base
{
}
