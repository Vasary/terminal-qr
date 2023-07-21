<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Model\User;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use Generator;

interface UserRepositoryInterface
{
    public function findByEmail(Email $email): ?User;

    public function find(): Generator;

    public function create(string $email, array $roles, string $hashedPassword): User;

    public function update(User $user): User;

    public function findById(Id $id): ?User;

    public function delete(User $user): void;
}
