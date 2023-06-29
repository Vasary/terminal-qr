<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Model\Gateway;
use App\Domain\Model\Payment;
use App\Domain\Model\QR;
use App\Domain\Model\Store;
use App\Infrastructure\Test\Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class PaymentFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly int $limit = 10)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $stores = $manager->getRepository(Store::class)->findAll();
        $randomStorePosition = rand(0, count($stores) - 1);
        $store = $stores[$randomStorePosition];

        $gateways = $manager->getRepository(Gateway::class)->findAll();
        $randomGatewayPosition = rand(0, count($gateways) - 1);
        $gateway = $gateways[$randomGatewayPosition];

        $store->addGateway($gateway);

        $manager->persist($store);
        $manager->flush();

        for ($i = 0; $i < $this->limit; $i++) {
                $payment = new Payment(
                    $faker->randomDigitNotZero() * 100,
                    $faker->randomDigitNotZero() * 100,
                    PaymentStatusEnum::Init,
                    $faker->url(),
                    $faker->currencyCode(),
                    $gateway,
                    $store,
                );

                $payment->withQR(new QR($faker->imageUrl(), $faker->imageUrl()));
                $manager->persist($payment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            GatewayFixtures::class,
            StoreFixtures::class,
        ];
    }
}
