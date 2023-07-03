<?php

declare(strict_types = 1);

namespace App\Tests\Application\Store;

use App\Application\Store\Business\Reader\StoreReader;
use App\Application\Store\Business\StoreFacade;
use App\Application\Store\Business\Writer\StoreWriter;
use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Store;
use App\Domain\Repository\StoreRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Persistence\DataFixtures\StoreFixtures;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Infrastructure\Test\Context\Model\GatewayContext;
use App\Infrastructure\Test\Context\Model\StoreContext;
use App\Shared\Transfer\SearchCriteria;
use App\Shared\Transfer\StoreCreate;
use App\Shared\Transfer\StoreDelete;
use App\Shared\Transfer\StoreUpdate;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;

final class StoreFacadeTest extends AbstractUnitTestCase
{
    public function testStoreFacadeShouldInitializeSuccessfully(): void
    {
        $writer = Mockery::mock(StoreWriter::class);
        $writer
            ->shouldReceive('write')
            ->never();

        $reader = Mockery::mock(StoreReader::class);
        $reader
            ->shouldReceive('all')
            ->never();

        $facade = new StoreFacade($writer, $reader);

        $this->assertInstanceOf(StoreFacade::class, $facade);
    }

    public function testStoreFacadeShouldCreateStore(): void
    {
        $transfer = StoreCreate::fromArray([
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
        ]);

        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);

        /** @var StoreRepositoryInterface $repository */
        $repository = $this->getContainer()->get(StoreRepositoryInterface::class);

        $store = $facade->create($transfer);

        $this->assertEquals($transfer->title(), $store->title());
        $this->assertEquals($transfer->description(), $store->description());
        $this->assertCount(1, iterator_to_array($repository->find()));
    }

    public function testStoreFacadeShouldRetrieveStores(): void
    {
        $this->loadFixtures(new StoreFixtures(10));

        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);

        $this->assertCount(10, iterator_to_array($facade->find()));
    }

    public function testShouldUpdateStore(): void
    {
        $store = StoreContext::create()();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($store);
        $entityManager->flush();

        $transfer = StoreUpdate::fromArray([
            'id' => (string) $store->id(),
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
        ]);

        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);

        /** @var StoreRepositoryInterface $repository */
        $repository = $this->getContainer()->get(StoreRepositoryInterface::class);

        $updatedStore = $facade->update($transfer);
        $storeInDatabase = $repository->findOne(Id::fromString($transfer->id()));

        $this->assertEquals((string) $updatedStore->title(), $transfer->title());
        $this->assertEquals((string) $updatedStore->description(), $transfer->description());
        $this->assertCount(1, iterator_to_array($facade->find()));

        $this->assertNotNull($storeInDatabase);

        $this->assertEquals((string) $storeInDatabase->title(), $transfer->title());
        $this->assertEquals((string) $storeInDatabase->description(), $transfer->description());
    }

    public function testShouldFailsOnUpdateNotExistingStore(): void
    {
        $this->expectException(NotFoundException::class);

        $transfer = StoreUpdate::fromArray([
            'id' => $this->faker->uuid(),
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
        ]);

        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);

        $facade->update($transfer);
    }

    public function testShouldRetrieveStoresWithPaginationData(): void
    {
        $this->loadFixtures(new StoreFixtures(3));
        $store = StoreContext::create()();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($store);
        $entityManager->flush();

        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);

        $searchCriteria = SearchCriteria::fromArray(
            [
                'fields' => [
                    [
                        'name' => 'title',
                        'value' => 'My store',
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
                'stores' => [(string) $store->id()]
            ]
        );

        $result = $facade->findByCriteria($searchCriteria);

        $this->assertCount(4, iterator_to_array($facade->find()));
        $this->assertCount(1, $result->aggregate());
        $this->assertEquals(1, $result->pages());
        $this->assertEquals(1, $result->items());
    }

    public function testShouldNotRetrieveStoresWithPaginationData(): void
    {
        $this->loadFixtures(new StoreFixtures(3));

        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);

        $searchCriteria = SearchCriteria::fromArray(
            [
                'fields' => [
                    [
                        'name' => 'title',
                        'value' => 'no-existing-store',
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

    public function testShouldRetrieveStoresOrderedByFieldAsc(): void
    {
        $this->loadFixtures(new StoreFixtures(10));

        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);
        $stores = array_map(
            fn (Store $store) => (string) $store->id(),
            iterator_to_array($facade->find())
        );

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
                'stores' => $stores,
            ]
        );

        $result = $facade->findByCriteria($searchCriteria);

        $titles = [];
        foreach ($result->aggregate() as $item) {
            /** @var Store $item */
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

    public function testShouldRetrieveStoresOrderedByFieldDesc(): void
    {
        $this->loadFixtures(new StoreFixtures(10));

        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);

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
            ]
        );

        $result = $facade->findByCriteria($searchCriteria);

        $titles = [];
        foreach ($result->aggregate() as $item) {
            /** @var Store $item */
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

    public function testShouldSuccessfullyDeleteStore(): void
    {
        $this->loadFixtures(new StoreFixtures(10));

        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);

        /** @var Store $randomStore */
        $randomStore = iterator_to_array($facade->find())[0];

        $facade->delete(new StoreDelete((string) $randomStore->id()));

        $result = $facade->findById($randomStore->id());

        $this->assertNull($result);
        $this->assertCount(9, iterator_to_array($facade->find()));
    }

    public function testShouldSuccessfullyAddGatewayToStore(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $store = StoreContext::create()();
        $gateway = GatewayContext::create()();

        $entityManager->persist($store);
        $entityManager->persist($gateway);
        $entityManager->flush();


        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);

        $facade->addGateway($store->id(), $gateway->id());

        $result = $facade->findById($store->id());

        $this->assertNotNull($result);
        $this->assertCount(1, $result->gateway());
    }

    public function testShouldSuccessfullyRemoveGatewayFromStore(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $store = StoreContext::create()();
        $gateway = GatewayContext::create()();

        $store->addGateway($gateway);
        $gateway->addStore($store);

        $entityManager->persist($store);
        $entityManager->persist($gateway);
        $entityManager->flush();


        /** @var StoreFacade $facade */
        $facade = $this->getContainer()->get(StoreFacade::class);

        $databaseStore = $facade->findById($store->id());

        $this->assertCount(1, $databaseStore->gateway());

        $facade->removeGateway($store->id(), $gateway->id());
        $storeWithNoGateways = $facade->findById($store->id());

        $this->assertCount(0, $storeWithNoGateways->gateway());
    }
}
