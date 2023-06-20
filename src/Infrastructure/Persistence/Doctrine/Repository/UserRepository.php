<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class UserRepository
{
    private ObjectRepository $objectRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(User::class);
    }

    public function create(): User
    {
        $user = new User();

        $this->entityManager->persist($user);

        return $user;
    }
}
