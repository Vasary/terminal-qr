<?php

declare(strict_types = 1);

namespace App\Application\User\Business\Reader;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Id;
use Generator;

final readonly class UserReader
{
    public function __construct(private UserRepositoryInterface $repository)
    {
    }

    public function findById(Id $id): ?User
    {
        return $this->repository->findById($id);
    }

    public function all(): Generator
    {
        return $this->repository->find();
    }
}
