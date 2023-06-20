<?php

declare(strict_types = 1);

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

abstract class AbstractUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
}
