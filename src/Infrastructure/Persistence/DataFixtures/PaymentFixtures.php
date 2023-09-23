<?php

declare(strict_types = 1);

namespace App\Infrastructure\Persistence\DataFixtures;

use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Model\Gateway;
use App\Domain\Model\Payment;
use App\Domain\Model\QR;
use App\Domain\Model\Store;
use App\Domain\ValueObject\Callback;
use App\Domain\ValueObject\Currency;
use App\Infrastructure\Test\Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class PaymentFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function __construct(private readonly int $limit = 100)
    {
    }

    public static function getGroups(): array
    {
        return ['demo'];
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
                    PaymentStatusEnum::new,
                    new Callback($faker->url()),
                    new Currency($faker->currencyCode()),
                    $gateway,
                    $store,
                    now(),
                );

                for ($j = 0; $j <= rand(1, 10); $j++) {
                    $payment->addLog($faker->realText());
                }

                if (0 === mt_rand(1, 2) % 2) {
                    $payment->withQR(new QR('/build/images/qr.svg', '/build/images/qr.svg', now()));
                    $payment->addParameter('token', $faker->lexify('??????????'));

                    for ($j = 0; $j < $faker->randomDigit(); $j++) {
                        $payment->addParameter(sprintf('Parameter %s', $j + 1), $faker->word());
                    }

                    $payment->withStatus(PaymentStatusEnum::successfully);
                }

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
