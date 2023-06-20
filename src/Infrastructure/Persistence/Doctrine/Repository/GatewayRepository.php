<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Gateway;
use App\Domain\Repository\GatewayRepositoryInterface;
use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Host;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Portal;
use App\Domain\ValueObject\Title;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class GatewayRepository implements GatewayRepositoryInterface
{
    private ObjectRepository $objectRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(Gateway::class);
    }

    public function findById(Id $id): ?Gateway
    {
        return
            $this->objectRepository->createQueryBuilder('g')
                ->select('g')
                ->where('g.id = :id')
                ->setParameter('id', (string) $id)
                ->getQuery()
                ->getOneOrNullResult();
    }

    public function create(string $title, string $callback, string $host, string $portal, string $currency, string $key): Gateway
    {
        $gateway = new Gateway(
            new Title($title),
            new Callback($callback),
            new Host($host),
            new Portal($portal),
            new Currency($currency),
            new Key($key),
        );

        $this->entityManager->persist($gateway);
        $this->entityManager->flush();

        return $gateway;
    }
}
