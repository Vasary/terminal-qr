<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Model\Payment;
use App\Domain\Model\QR;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Test\Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class PaymentFixtures extends Fixture
{
    public function __construct(private readonly int $limit = 10)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < $this->limit; $i++) {
                $payment = new Payment(
                    $faker->randomDigitNotZero(),
                    $faker->randomDigitNotZero(),
                    $faker->randomNumber(1),
                    $faker->url(),
                    Id::fromString($faker->uuid()),
                    Id::fromString($faker->uuid()),
                );

                $payment->withQR(new QR($faker->imageUrl(), $faker->imageUrl()));
                $manager->persist($payment);
        }

        $manager->flush();
    }
}
