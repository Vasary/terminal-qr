<?php

declare(strict_types = 1);

namespace App\Tests\Application\Store;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Shared\Transfer\GatewayCreate;

final class GatewayFacadeTest extends AbstractUnitTestCase
{
    public function testGatewayFacadeShouldCreateGateway(): void
    {
        $transfer = GatewayCreate::fromArray([
            'title' => $this->faker->title(),
            'callback' => $this->faker->url(),
            'host' => $this->faker->domainName(),
            'portal' => $this->faker->company(),
            'currency' => $this->faker->currencyCode(),
            'key' => $this->faker->word(),
        ]);

        /** @var GatewayFacadeInterface $facade */
        $facade = $this->getContainer()->get(GatewayFacadeInterface::class);
        $gateway = $facade->create($transfer);

        $this->assertEquals($transfer->title(), (string) $gateway->title());
        $this->assertEquals($transfer->callback(), (string) $gateway->callback());
        $this->assertEquals($transfer->host(), (string) $gateway->host());
        $this->assertEquals($transfer->portal(), (string) $gateway->portal());
        $this->assertEquals($transfer->currency(), (string) $gateway->currency());
        $this->assertEquals($transfer->key(), (string) $gateway->key());
    }
}
