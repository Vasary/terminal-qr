<?php

declare(strict_types = 1);

namespace App\Application\Gateway\Business;

use App\Application\Gateway\Business\Reader\GatewayReader;
use App\Application\Gateway\Business\Writer\GatewayWriter;
use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Pagination;
use App\Shared\Transfer\GatewayCreate;
use App\Shared\Transfer\GatewayDelete;
use App\Shared\Transfer\GatewayUpdate;
use App\Shared\Transfer\SearchCriteria;
use Generator;

final readonly class GatewayFacade implements GatewayFacadeInterface
{
    public function __construct(private GatewayReader $reader, private GatewayWriter $writer,)
    {
    }

    public function findById(Id $id): ?Gateway
    {
        return $this->reader->findById($id);
    }

    public function create(GatewayCreate $transfer, array $credentials): Gateway
    {
        return $this->writer->write($transfer, $credentials);
    }

    public function find(): Generator
    {
        return $this->reader->all();
    }

    /**
     * @throws NotFoundException
     */
    public function update(GatewayUpdate $transfer, array $credentials): Gateway
    {
        return $this->writer->update($transfer, $credentials);
    }

    public function findByCriteria(SearchCriteria $searchCriteria): Pagination
    {
        return $this->reader->findByCriteria($searchCriteria);
    }

    /**
     * @throws NotFoundException
     */
    public function delete(GatewayDelete $transfer): void
    {
        $this->writer->delete($transfer);
    }

    public function findByKey(Key $key): ?Gateway
    {
        return $this->reader->findByKey($key);
    }
}
