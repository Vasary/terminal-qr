<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Application\User\User;
use App\Domain\Model\User as DomainUser;
use App\Domain\ValueObject\Email;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public static function getGroups(): array
    {
        return ['users', 'demo'];
    }

    public function load(ObjectManager $manager): void
    {
        $administrator = $this->createUser('admin@vasary.com', 'admin', 'ROLE_ADMIN');
        $storeManager = $this->createUser('manager@vasary.com', 'manager', 'ROLE_MANAGER');

        $manager->persist($administrator->getDomainUser());
        $manager->persist($storeManager->getDomainUser());

        $manager->flush();
    }

    private function createUser(string $email, string $password, string $additionalRole): User
    {
        $user = new User(new DomainUser(new Email($email), now()));

        $hashedPassword = $this->hasher->hashPassword($user, $password);

        $domainUser = $user->getDomainUser();
        $domainUser->withPassword($hashedPassword);
        $domainUser->addRole('ROLE_USER');
        $domainUser->addRole($additionalRole);

        return $user;
    }
}
