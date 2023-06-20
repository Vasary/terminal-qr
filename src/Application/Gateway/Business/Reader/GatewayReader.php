<?php

declare(strict_types = 1);

namespace App\Application\Gateway\Business\Reader;

use App\Domain\Model\Gateway;
use App\Domain\Repository\GatewayRepositoryInterface;
use App\Domain\ValueObject\Id;

final readonly class GatewayReader
{
    public function __construct(private GatewayRepositoryInterface $repository)
    {
    }

    public function findById(Id $id): ?Gateway
    {
        return $this->repository->findById($id);
    }
}
