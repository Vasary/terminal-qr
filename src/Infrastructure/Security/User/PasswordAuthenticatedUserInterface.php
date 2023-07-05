<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\User;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface as Base;

interface PasswordAuthenticatedUserInterface extends Base
{
}
