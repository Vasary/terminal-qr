<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\Store;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Generator;

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

    public function find(): Generator
    {
        return
            $this->objectRepository->createQueryBuilder('u')
                ->orderBy('u.createdAt', 'DESC')
                ->getQuery()
                ->toIterable();
    }

    public function create(string $email, array $roles, string $hashedPassword): User
    {
        $user = new User(new Email($email));

        foreach ($roles as $role) {
            $user->addRole($role);
        }

        $user->withPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function update(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function findById(Id $id): ?User
    {
        return
            $this->objectRepository->createQueryBuilder('u')
                ->select('u')
                ->where('u.id = :id')
                ->setParameter('id', (string) $id)
                ->getQuery()
                ->getOneOrNullResult();
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
