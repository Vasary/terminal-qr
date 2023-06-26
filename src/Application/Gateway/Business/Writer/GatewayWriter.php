<?php

declare(strict_types = 1);

namespace App\Application\Gateway\Business\Writer;

use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Gateway;
use App\Domain\Repository\GatewayRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Shared\Transfer\GatewayCreate;
use App\Shared\Transfer\GatewayDelete;
use App\Shared\Transfer\GatewayUpdate;

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
            $this->generateKey($transfer),
        );
    }

    /**
     * @throws NotFoundException
     */
    public function update(GatewayUpdate $transfer): Gateway
    {
        $id = Id::fromString($transfer->id());
        $gateway = $this->getGateway($id);

        $gateway
            ->withTitle($transfer->title())
            ->withCallback($transfer->callback())
            ->withHost($transfer->host())
            ->withPortal($transfer->portal())
            ->withCurrency($transfer->currency())
        ;

        $this->repository->update($gateway);

        return $gateway;
    }

    /**
     * @throws NotFoundException
     */
    public function delete(GatewayDelete $transfer): void
    {
        $id = Id::fromString($transfer->id());
        $gateway = $this->getGateway($id);

        $this->repository->delete($gateway);
    }

    private function generateKey(GatewayCreate $transfer): string
    {
        return md5($transfer->title() . $transfer->host() . $transfer->currency() . $transfer->callback());
    }

    /**
     * @throws NotFoundException
     */
    private function getGateway(Id $id): Gateway
    {
        $gateway = $this->repository->findById($id);

        if (null === $gateway) {
            throw new NotFoundException(Gateway::class, $id);
        }

        return $gateway;
    }
}
