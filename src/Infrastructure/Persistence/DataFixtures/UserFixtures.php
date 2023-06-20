<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Model\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(new User());
        $manager->flush();
    }
}
