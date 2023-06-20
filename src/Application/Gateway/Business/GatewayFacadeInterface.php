<?php

declare(strict_types = 1);

namespace App\Application\Gateway\Business;

use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Id;
use App\Shared\Transfer\GatewayCreate;

interface GatewayFacadeInterface
{
    public function findById(Id $id): ?Gateway;

    public function create(GatewayCreate $transfer): Gateway;
}
