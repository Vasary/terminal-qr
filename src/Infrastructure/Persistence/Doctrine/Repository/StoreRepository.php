<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Store;
use App\Domain\Repository\StoreRepositoryInterface;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\Description;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Title;
use App\Shared\Transfer\OrderByField;
use App\Shared\Transfer\SearchField;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ObjectRepository;
use Generator;
use Doctrine\ORM\Tools\Pagination\Paginator;

final class StoreRepository implements StoreRepositoryInterface
{
    private ObjectRepository $objectRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(Store::class);
    }

    public function create(string $code, string $title, string $description): Store
    {
        $store = new Store(
            new Title($title),
            new Code($code),
            new Description($description)
        );

        $this->entityManager->persist($store);
        $this->entityManager->flush();

        return $store;
    }

    public function find(): Generator
    {
        return
            $this->objectRepository->createQueryBuilder('s')
                ->orderBy('s.createdAt', 'DESC')
                ->getQuery()
                ->toIterable();
    }

    public function findOne(Id $id): ?Store
    {
        return
            $this->objectRepository->createQueryBuilder('s')
                ->select('s')
                ->where('s.id = :id')
                ->setParameter('id', (string)$id)
                ->getQuery()
                ->getOneOrNullResult();
    }

    public function findByCriteria(array $searchFields, array $orderBy, int $page, int $size): Paginator
    {
        $queryBuilder = $this->objectRepository->createQueryBuilder('s');
        $queryBuilder->select('s');

        if (count($searchFields) > 0) {
            foreach ($searchFields as $field) {
                /** @var SearchField $field */
                $queryBuilder->orWhere(
                    $queryBuilder->expr()->like(
                        sprintf('s.%s', $field->name()),
                        $queryBuilder->expr()->literal('%' . $field->value() . '%')
                    )
                );
            }
        }

        if (count($orderBy) > 0) {
            foreach ($orderBy as $orderByField) {
                /** @var OrderByField $orderByField */
                $queryBuilder->addOrderBy(
                    sprintf('s.%s', $orderByField->field()),
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

    public function update(Store $store): void
    {
        $this->entityManager->persist($store);
        $this->entityManager->flush();
    }

    public function delete(Store $store): void
    {
        $this->entityManager->remove($store);
        $this->entityManager->flush();
    }

    public function findByCode(Code $code): ?Store
    {
        return
            $this->objectRepository->createQueryBuilder('s')
                ->select('s')
                ->where('s.code = :code')
                ->setParameter('code', (string) $code)
                ->getQuery()
                ->getOneOrNullResult();
    }
}
