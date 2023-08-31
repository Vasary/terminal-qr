<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Credentials\Stub;
use App\Domain\ValueObject\Key;
use App\Domain\ValueObject\Title;
use App\Infrastructure\Test\Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use function App\Infrastructure\DateTime\now;

final class GatewayFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private readonly int $limit = 10) {}

    public static function getGroups(): array
    {
        return ['demo'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < $this->limit; $i++) {
            $manager->persist(
                new Gateway(
                    new Title($faker->company()),
                    new Callback($faker->url()),
                    new Key($faker->uuid()),
                    new Stub('', ''),
                    now(),
                )
            );
        }

        $manager->flush();
    }
}
