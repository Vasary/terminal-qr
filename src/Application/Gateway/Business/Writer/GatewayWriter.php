<?php

declare(strict_types = 1);

namespace App\Application\Gateway\Business\Writer;

use App\Domain\Model\Gateway;
use App\Domain\Repository\GatewayRepositoryInterface;
use App\Shared\Transfer\GatewayCreate;

final readonly class GatewayWriter
{
    public function __construct(private GatewayRepositoryInterface $repository)
    {
    }

    public function write(GatewayCreate $transfer): Gateway
    {
        return $this->repository->create(
            $transfer->title(),
            $transfer->callback(),
            $transfer->host(),
            $transfer->portal(),
            $transfer->currency(),
            $transfer->key(),
        );
    }
}
