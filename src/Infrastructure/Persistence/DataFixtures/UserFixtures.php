<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Application\User\User;
use App\Domain\Model\User as DomainUser;
use App\Domain\ValueObject\Email;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $administrator = $this->createUser('admin@vasary.com', 'admin', true);
        $storeManager = $this->createUser('manager@vasary.com', 'manager', true);

        $manager->persist($administrator->getDomainUser());
        $manager->persist($storeManager->getDomainUser());

        $manager->flush();
    }

    private function createUser(string $email, string $password, bool $isAdmin): User
    {
        $user = new User(new DomainUser(new Email($email)));

        $hashedPassword = $this->hasher->hashPassword($user, $password);

        $domainUser = $user->getDomainUser();
        $domainUser->withPassword($hashedPassword);
        $domainUser->addRole('ROLE_USER');

        if ($isAdmin) {
            $domainUser->addRole('ROLE_ADMIN');
        }

        return $user;
    }
}
