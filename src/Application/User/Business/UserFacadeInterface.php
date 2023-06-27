<?php

declare(strict_types = 1);

namespace App\Application\User\Business;

use App\Domain\Model\User;
use App\Domain\ValueObject\Id;
use App\Shared\Transfer\UserCreate;
use App\Shared\Transfer\UserDelete;
use App\Shared\Transfer\UserUpdate;
use Generator;

interface UserFacadeInterface
{
    public function findById(Id $id): ?User;

    public function create(UserCreate $transfer): User;

    public function find(): Generator;

    public function update(UserUpdate $transfer): User;

    public function delete(UserDelete $transfer): void;
}
