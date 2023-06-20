<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Model\Store;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\Description;
use App\Domain\ValueObject\Title;
use App\Infrastructure\Test\Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class StoreFixtures extends Fixture
{
    public function __construct(private readonly int $limit = 10) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < $this->limit; $i++) {
            $manager->persist(
                new Store(
                    new Title($faker->company()),
                    new Code($faker->word()),
                    new Description($faker->text()),
                )
            );
        }

        $manager->flush();
    }
}
