<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Currency;
use App\Domain\ValueObject\Host;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Portal;
use App\Domain\ValueObject\Title;
use App\Infrastructure\Test\Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class GatewayFixtures extends Fixture
{
    public function __construct(private readonly int $limit) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < $this->limit; $i++) {
            $manager->persist(
                new Gateway(
                    new Title($faker->company()),
                    new Callback($faker->url()),
                    new Host($faker->domainName()),
                    new Portal($faker->domainName()),
                    new Currency($faker->currencyCode()),
                    new Key($faker->word()),
                )
            );
        }

        $manager->flush();
    }
}
