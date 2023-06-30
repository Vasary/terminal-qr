<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Model\Gateway;
use App\Domain\Model\Payment;
use App\Domain\Model\Store;
use App\Domain\Repository\PaymentRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Shared\Transfer\OrderByField;
use App\Shared\Transfer\SearchField;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ObjectRepository;

final class PaymentRepository implements PaymentRepositoryInterface
{
    private ObjectRepository $objectRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(Payment::class);
    }

    public function findByCriteria(array $searchFields, array $orderBy, int $page, int $size): Paginator
    {
        $queryBuilder = $this->objectRepository->createQueryBuilder('p');

        if (count($searchFields) > 0) {
            foreach ($searchFields as $field) {
                /** @var SearchField $field */
                $queryBuilder->orWhere(
                    $queryBuilder->expr()->like(
                        sprintf('p.%s', $field->name()),
                        $queryBuilder->expr()->literal('%' . $field->value() . '%')
                    )
                );
            }
        }

        if (count($orderBy) > 0) {
            foreach ($orderBy as $orderByField) {
                /** @var OrderByField $orderByField */
                $queryBuilder->addOrderBy(
                    sprintf('p.%s', $orderByField->field()),
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

    public function findOne(Id $id): ?Payment
    {
        return
            $this->objectRepository->createQueryBuilder('p')
                ->select('p')
                ->where('p.id = :id')
                ->setParameter('id', (string) $id)
                ->getQuery()
                ->getOneOrNullResult();
    }

    public function update(Payment $payment): void
    {
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }

    public function create(int $amount, Store $store, Gateway $gateway): Payment
    {
        $payment = new Payment(
            $amount,
            0,
            PaymentStatusEnum::Init,
            $gateway->callback(),
            $gateway->currency(),
            $gateway,
            $store,
        );

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        return $payment;
    }
}
