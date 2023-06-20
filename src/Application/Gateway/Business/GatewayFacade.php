<?php

declare(strict_types = 1);

namespace App\Application\Gateway\Business;

use App\Application\Gateway\Business\Reader\GatewayReader;
use App\Application\Gateway\Business\Writer\GatewayWriter;
use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Id;
use App\Shared\Transfer\GatewayCreate;

final readonly class GatewayFacade implements GatewayFacadeInterface
{
    public function __construct(private GatewayReader $reader, private GatewayWriter $writer,)
    {
    }

    public function findById(Id $id): ?Gateway
    {
        return $this->reader->findById($id);
    }

    public function create(GatewayCreate $transfer): Gateway
    {
        return $this->writer->write($transfer);
    }
}
