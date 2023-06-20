<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Model\User;
use App\Domain\ValueObject\Email;

interface UserRepositoryInterface
{
    public function findByEmail(Email $email): ?User;
}
