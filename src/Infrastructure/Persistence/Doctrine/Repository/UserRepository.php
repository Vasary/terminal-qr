<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class UserRepository implements UserRepositoryInterface
{
    private ObjectRepository $objectRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(User::class);
    }

    public function findByEmail(Email $email): ?User
    {
        return
            $this->objectRepository->createQueryBuilder('u')
                ->select('u')
                ->where('u.email = :email')
                ->setParameter('email', (string) $email)
                ->getQuery()
                ->getOneOrNullResult();
    }
}
