<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Gateway;
use App\Domain\Repository\GatewayRepositoryInterface;
use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Credentials\Credential;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Host;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Portal;
use App\Domain\ValueObject\Title;
use App\Infrastructure\Persistence\Doctrine\Type\CredentialType;
use App\Shared\Transfer\OrderByField;
use App\Shared\Transfer\SearchField;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ObjectRepository;
use Generator;


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

    public function create(string $title, string $callback, string $key, Credential $credential): Gateway
    {
        $gateway = new Gateway(
            new Title($title),
            new Callback($callback),
            new Key($key),
            $credential,
            now()
        );

        $this->entityManager->persist($gateway);
        $this->entityManager->flush();

        return $gateway;
    }

    public function find(): Generator
    {
        return
            $this->objectRepository->createQueryBuilder('g')
                ->orderBy('g.createdAt', 'DESC')
                ->getQuery()
                ->toIterable();
    }

    public function update(Gateway $gateway): void
    {
        $this->entityManager->persist($gateway);
        $this->entityManager->flush();
    }

    public function findByCriteria(array $searchFields, array $orderBy, int $page, int $size): Paginator
    {
        $queryBuilder = $this->objectRepository->createQueryBuilder('g');

        if (count($searchFields) > 0) {
            foreach ($searchFields as $field) {
                /** @var SearchField $field */
                $queryBuilder->orWhere(
                    $queryBuilder->expr()->like(
                        sprintf('g.%s', $field->name()),
                        $queryBuilder->expr()->literal('%' . $field->value() . '%')
                    )
                );
            }
        }

        if (count($orderBy) > 0) {
            foreach ($orderBy as $orderByField) {
                /** @var OrderByField $orderByField */
                $queryBuilder->addOrderBy(
                    sprintf('g.%s', $orderByField->field()),
                    'desc' === strtolower($orderByField->direction())
                        ? Criteria::DESC
                        : Criteria::ASC
                );
            }
        }

        $paginator = new Paginator($queryBuilder->getQuery());

        $paginator
            ->getQuery()
            ->setFirstResult($size * ($page - 1))
            ->setMaxResults($size);

        return $paginator;
    }

    public function delete(Gateway $gateway): void
    {
        $this->entityManager->remove($gateway);
        $this->entityManager->flush();
    }

    public function findByKey(Key $key): ?Gateway
    {
        return
            $this->objectRepository->createQueryBuilder('g')
                ->select('g')
                ->where('g.key = :jey')
                ->setParameter('jey', (string) $key)
                ->getQuery()
                ->getOneOrNullResult();
    }
}
