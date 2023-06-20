<?php

declare(strict_types = 1);

namespace App\Application\Security;

use App\Domain\Model\User;

interface SecurityInterface
{
    public function getDomainUser(): User;
}
