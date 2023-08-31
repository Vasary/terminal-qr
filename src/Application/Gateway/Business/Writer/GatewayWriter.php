<?php

declare(strict_types = 1);

namespace App\Application\Gateway\Business\Writer;

use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Gateway;
use App\Domain\Repository\GatewayRepositoryInterface;
use App\Domain\ValueObject\Credentials\Credential;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Serializer\Serializer;
use App\Shared\Transfer\GatewayCreate;
use App\Shared\Transfer\GatewayDelete;
use App\Shared\Transfer\GatewayUpdate;

final readonly class GatewayWriter
{
    public function __construct(private GatewayRepositoryInterface $repository)
    {
    }

    public function write(GatewayCreate $transfer, array $credentialsData): Gateway
    {
        $key = (string) Id::create();

        /** @var Credential $credentials */
        $credentials = Serializer::create()->toType($credentialsData, Credential::class);

        return $this->repository->create(
            $transfer->title(),
            $transfer->callback(),
            $key,
            $credentials
        );
    }

    /**
     * @throws NotFoundException
     */
    public function update(GatewayUpdate $transfer, array $credentialsData): Gateway
    {
        $id = Id::fromString($transfer->id());
        $gateway = $this->getGateway($id);

        /** @var Credential $credentials */
        $credentials = Serializer::create()->toType($credentialsData, Credential::class);

        $gateway
            ->withTitle($transfer->title())
            ->withCallback($transfer->callback())
            ->withCredentials($credentials)
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
