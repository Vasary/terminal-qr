<?php

declare(strict_types = 1);

namespace App\Tests\Application\Gateway;

use App\Application\Gateway\Business\GatewayFacade;
use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Gateway;
use App\Domain\Repository\GatewayRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Persistence\DataFixtures\GatewayFixtures;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Infrastructure\Test\Context\Model\GatewayContext;
use App\Shared\Transfer\GatewayCreate;
use App\Shared\Transfer\GatewayDelete;
use App\Shared\Transfer\GatewayUpdate;
use App\Shared\Transfer\SearchCriteria;
use Doctrine\ORM\EntityManagerInterface;

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
        ]);

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);
        $gateway = $facade->create($transfer);

        $this->assertEquals($transfer->title(), (string) $gateway->title());
        $this->assertEquals($transfer->callback(), (string) $gateway->callback());
        $this->assertEquals($transfer->host(), (string) $gateway->host());
        $this->assertEquals($transfer->portal(), (string) $gateway->portal());
        $this->assertEquals($transfer->currency(), (string) $gateway->currency());
    }

    public function testGatewayFacadeShouldRetrieveGateways(): void
    {
        $this->loadFixtures(new GatewayFixtures(10));

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $this->assertCount(10, iterator_to_array($facade->find()));
    }

    public function testShouldUpdateGateway(): void
    {
        $gateway = GatewayContext::create()();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($gateway);
        $entityManager->flush();

        $transfer = GatewayUpdate::fromArray([
            'id' => (string) $gateway->id(),
            'title' => $this->faker->title(),
            'callback' => $this->faker->url(),
            'host' => $this->faker->domainName(),
            'portal' => $this->faker->company(),
            'currency' => $this->faker->currencyCode(),
        ]);

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        /** @var GatewayRepositoryInterface $repository */
        $repository = $this->getContainer()->get(GatewayRepositoryInterface::class);

        $updatedGateway = $facade->update($transfer);
        $gatewayInDatabase = $repository->findById(Id::fromString($transfer->id()));

        $this->assertEquals((string) $updatedGateway->id(), $transfer->id());
        $this->assertEquals((string) $updatedGateway->title(), $transfer->title());
        $this->assertEquals((string) $updatedGateway->callback(), $transfer->callback());
        $this->assertEquals((string) $updatedGateway->host(), $transfer->host());
        $this->assertEquals((string) $updatedGateway->portal(), $transfer->portal());
        $this->assertEquals((string) $updatedGateway->currency(), $transfer->currency());
        $this->assertCount(1, iterator_to_array($facade->find()));

        $this->assertNotNull($gatewayInDatabase);

        $this->assertEquals((string) $gatewayInDatabase->id(), $transfer->id());
        $this->assertEquals((string) $gatewayInDatabase->title(), $transfer->title());
        $this->assertEquals((string) $gatewayInDatabase->callback(), $transfer->callback());
        $this->assertEquals((string) $gatewayInDatabase->host(), $transfer->host());
        $this->assertEquals((string) $gatewayInDatabase->portal(), $transfer->portal());
        $this->assertEquals((string) $gatewayInDatabase->currency(), $transfer->currency());
    }


    public function testShouldFailsWithNotExistingGatewayId(): void
    {
        $this->expectException(NotFoundException::class);

        $transfer = GatewayUpdate::fromArray([
            'id' => $this->faker->uuid(),
            'title' => $this->faker->title(),
            'callback' => $this->faker->url(),
            'host' => $this->faker->domainName(),
            'portal' => $this->faker->company(),
            'currency' => $this->faker->currencyCode(),
        ]);

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $facade->update($transfer);
    }

    public function testShouldRetrieveGatewayWithPaginationData(): void
    {
        $this->loadFixtures(new GatewayFixtures(3));
        $gateway = GatewayContext::create()();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($gateway);
        $entityManager->flush();

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $searchCriteria = SearchCriteria::fromArray(
            [
                'fields' => [
                    [
                        'name' => 'title',
                        'value' => 'My gateway',
                    ],
                ],
                'orderBy' => [
                    [
                        'field' => 'createdAt',
                        'direction' => 'desc',
                    ],
                ],
                'page' => 1,
                'limit' => 4,
                'stores' => [],
            ]
        );

        $result = $facade->findByCriteria($searchCriteria);

        $this->assertCount(4, iterator_to_array($facade->find()));
        $this->assertCount(1, $result->aggregate());
        $this->assertEquals(1, $result->pages());
        $this->assertEquals(1, $result->items());
    }

    public function testShouldNotRetrieveGatewaysWithPaginationData(): void
    {
        $this->loadFixtures(new GatewayFixtures(3));

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $searchCriteria = SearchCriteria::fromArray(
            [
                'fields' => [
                    [
                        'name' => 'title',
                        'value' => 'no-existing-gateway',
                    ],
                ],
                'orderBy' => [],
                'page' => 1,
                'limit' => 4,
                'stores' => [],
            ]
        );

        $result = $facade->findByCriteria($searchCriteria);

        $this->assertCount(3, iterator_to_array($facade->find()));
        $this->assertCount(0, $result->aggregate());
        $this->assertEquals(0, $result->pages());
        $this->assertEquals(0, $result->items());
    }

    public function testShouldRetrieveGatewaysOrderedByFieldAsc(): void
    {
        $this->loadFixtures(new GatewayFixtures(10));

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $searchCriteria = SearchCriteria::fromArray(
            [
                'fields' => [],
                'orderBy' => [
                    [
                        'field' => 'title',
                        'direction' => 'asc',
                    ],
                ],
                'page' => 1,
                'limit' => 10,
                'stores' => [],
            ]
        );

        $result = $facade->findByCriteria($searchCriteria);

        $titles = [];
        foreach ($result->aggregate() as $item) {
            /** @var Gateway $item */
            $titles[] = (string) $item->title();
        }

        $expectedSorted = [...$titles];

        asort($expectedSorted);

        $this->assertCount(10, iterator_to_array($facade->find()));
        $this->assertCount(10, $result->aggregate());
        $this->assertEquals(1, $result->pages());
        $this->assertEquals(10, $result->items());
        $this->assertSame($expectedSorted, $titles);
    }

    public function testShouldRetrieveGatewaysOrderedByFieldDesc(): void
    {
        $this->loadFixtures(new GatewayFixtures(10));

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $searchCriteria = SearchCriteria::fromArray(
            [
                'fields' => [],
                'orderBy' => [
                    [
                        'field' => 'title',
                        'direction' => 'desc',
                    ],
                ],
                'page' => 1,
                'limit' => 10,
                'stores' => [],
            ]
        );

        $result = $facade->findByCriteria($searchCriteria);

        $titles = [];
        foreach ($result->aggregate() as $item) {
            /** @var Gateway $item */
            $titles[] = (string) $item->title();
        }

        $expectedSorted = [...$titles];

        arsort($expectedSorted);

        $this->assertCount(10, iterator_to_array($facade->find()));
        $this->assertCount(10, $result->aggregate());
        $this->assertEquals(1, $result->pages());
        $this->assertEquals(10, $result->items());
        $this->assertSame($expectedSorted, $titles);
    }

    public function testShouldSuccessfullyDeleteGateway(): void
    {
        $this->loadFixtures(new GatewayFixtures(10));

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        /** @var Gateway $randomGateway */
        $randomGateway = iterator_to_array($facade->find())[0];

        $facade->delete(new GatewayDelete((string) $randomGateway->id()));

        $result = $facade->findById($randomGateway->id());

        $this->assertNull($result);
        $this->assertCount(9, iterator_to_array($facade->find()));
    }
}
