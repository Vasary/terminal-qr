<?php

declare(strict_types = 1);

namespace App\Infrastructure\Security;

use App\Application\Security\SecurityInterface;
use App\Domain\Model\User;
use Symfony\Bundle\SecurityBundle\Security as SymfonySecurity;

final readonly class Security implements SecurityInterface
{
    public function __construct(private SymfonySecurity $security)
    {
    }

    public function getDomainUser(): User
    {
        return $this->security->getUser()->getDomainUser();
    }
}
