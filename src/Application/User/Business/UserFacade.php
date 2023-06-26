<?php

declare(strict_types = 1);

namespace App\Application\User\Business;

use App\Application\User\Business\Reader\UserReader;
use App\Application\User\Business\Writer\UserWriter;
use App\Domain\Exception\NotFoundException;
use App\Domain\Model\User;
use App\Domain\ValueObject\Id;
use App\Shared\Transfer\UserCreate;
use App\Shared\Transfer\UserDelete;
use App\Shared\Transfer\UserUpdate;
use Generator;

final readonly class UserFacade implements UserFacadeInterface
{
    public function __construct(private UserReader $reader, private UserWriter $writer,)
    {
    }

    public function findById(Id $id): ?User
    {
        return $this->reader->findById($id);
    }

    public function create(UserCreate $transfer): User
    {
        return $this->writer->create($transfer);
    }

    public function find(): Generator
    {
        return $this->reader->all();
    }

    /**
     * @throws NotFoundException
     */
    public function update(UserUpdate $transfer): User
    {
        return $this->writer->update($transfer);
    }

    /**
     * @throws NotFoundException
     */
    public function delete(UserDelete $transfer): void
    {
        $this->writer->delete($transfer);
    }
}
