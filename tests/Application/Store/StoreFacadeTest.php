<?php

declare(strict_types = 1);

use App\Application\Store\Business\StoreFacade;
use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Store;
use App\Domain\Repository\StoreRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Persistence\DataFixtures\StoreFixtures;
use App\Infrastructure\Test\Context\Model\GatewayContext;
use App\Infrastructure\Test\Context\Model\StoreContext;
use App\Infrastructure\Test\Context\Model\UserContext;
use App\Shared\Transfer\SearchCriteria;
use App\Shared\Transfer\StoreCreate;
use App\Shared\Transfer\StoreDelete;
use App\Shared\Transfer\StoreUpdate;
use Doctrine\ORM\EntityManagerInterface;

test('store facade should create store', function () {
    $transfer = StoreCreate::fromArray([
        'title' => faker()->title(),
        'description' => faker()->text(),
    ]);

    /** @var StoreFacade $facade */
    $facade = $this->getContainer()->get(StoreFacade::class);

    /** @var StoreRepositoryInterface $repository */
    $repository = $this->getContainer()->get(StoreRepositoryInterface::class);

    $store = $facade->create($transfer);

    expect($store->title())->toEqual($transfer->title())
        ->and($store->description())->toEqual($transfer->description())
        ->and($store->createdAt())->toBeInstanceOf(DateTimeImmutable::class)
        ->and($store->updatedAt())->toBeInstanceOf(DateTimeImmutable::class)
        ->and(iterator_to_array($repository->find()))->toHaveCount(1);
});

test('store facade should retrieve stores', function () {
    $this->loadFixtures(new StoreFixtures(10));

    /** @var StoreFacade $facade */
    $facade = $this->getContainer()->get(StoreFacade::class);

    expect(iterator_to_array($facade->find()))->toHaveCount(10);
});

test('should update store', function () {
    $store = StoreContext::create()();

    /** @var EntityManagerInterface $entityManager */
    $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

    $entityManager->persist($store);
    $entityManager->flush();

    $transfer = StoreUpdate::fromArray([
        'id' => (string) $store->id(),
        'title' => faker()->title(),
        'description' => faker()->text(),
    ]);

    /** @var StoreFacade $facade */
    $facade = $this->getContainer()->get(StoreFacade::class);

    /** @var StoreRepositoryInterface $repository */
    $repository = $this->getContainer()->get(StoreRepositoryInterface::class);

    $updatedStore = $facade->update($transfer);
    $storeInDatabase = $repository->findOne(Id::fromString($transfer->id()));

    expect($transfer->title())->toEqual((string) $updatedStore->title())
        ->and($transfer->description())->toEqual((string) $updatedStore->description())
        ->and(iterator_to_array($facade->find()))->toHaveCount(1)
        ->and($storeInDatabase)->not->toBeNull()
        ->and($transfer->title())->toEqual((string) $storeInDatabase->title())
        ->and($transfer->description())->toEqual((string) $storeInDatabase->description());

});

test('should fails on update not existing store', function () {
    $this->expectException(NotFoundException::class);

    $transfer = StoreUpdate::fromArray([
        'id' => faker()->uuid(),
        'title' => faker()->title(),
        'description' => faker()->text(),
    ]);

    /** @var StoreFacade $facade */
    $facade = $this->getContainer()->get(StoreFacade::class);

    $facade->update($transfer);
});

test('should retrieve stores with pagination data', function () {
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
            'stores' => [(string) $store->id()],
        ],
    );

    $result = $facade->findByCriteria($searchCriteria);

    expect(iterator_to_array($facade->find()))->toHaveCount(4)
        ->and($result->aggregate())->toHaveCount(1)
        ->and($result->pages())->toEqual(1)
        ->and($result->items())->toEqual(1);
});

test('should not retrieve stores with pagination data', function () {
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
        ],
    );

    $result = $facade->findByCriteria($searchCriteria);

    expect(iterator_to_array($facade->find()))->toHaveCount(3)
        ->and($result->aggregate())->toHaveCount(0)
        ->and($result->pages())->toEqual(0)
        ->and($result->items())->toEqual(0);
});

test('should retrieve stores ordered by field asc', function () {
    $this->loadFixtures(new StoreFixtures(10));

    /** @var StoreFacade $facade */
    $facade = $this->getContainer()->get(StoreFacade::class);
    $stores = array_map(
        fn(Store $store) => (string) $store->id(),
        iterator_to_array($facade->find()),
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
        ],
    );

    $result = $facade->findByCriteria($searchCriteria);

    $titles = [];
    foreach ($result->aggregate() as $item) {
        /** @var Store $item */
        $titles[] = (string) $item->title();
    }

    $expectedSorted = [...$titles];

    asort($expectedSorted);

    expect(iterator_to_array($facade->find()))->toHaveCount(10)
        ->and($result->aggregate())->toHaveCount(10)
        ->and($result->pages())->toEqual(1)
        ->and($result->items())->toEqual(10)
        ->and($titles)->toBe($expectedSorted);
});

test('should retrieve stores ordered by field desc', function () {
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
        ],
    );

    $result = $facade->findByCriteria($searchCriteria);

    $titles = [];
    foreach ($result->aggregate() as $item) {
        /** @var Store $item */
        $titles[] = (string) $item->title();
    }

    $expectedSorted = [...$titles];

    arsort($expectedSorted);

    expect(iterator_to_array($facade->find()))->toHaveCount(10)
        ->and($result->aggregate())->toHaveCount(10)
        ->and($result->pages())->toEqual(1)
        ->and($result->items())->toEqual(10)
        ->and($titles)->toBe($expectedSorted);
});

test('should successfully delete store', function () {
    $this->loadFixtures(new StoreFixtures(10));

    /** @var StoreFacade $facade */
    $facade = $this->getContainer()->get(StoreFacade::class);

    /** @var Store $randomStore */
    $randomStore = iterator_to_array($facade->find())[0];

    $facade->delete(new StoreDelete((string) $randomStore->id()));

    $result = $facade->findById($randomStore->id());

    expect($result)->toBeNull()
        ->and(iterator_to_array($facade->find()))->toHaveCount(9);
});

test('should successfully add gateway to store', function () {
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

    expect($result)->not->toBeNull()
        ->and($result->gateway())->toHaveCount(1);
});

test('should successfully remove gateway from store', function () {
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

    expect($databaseStore->gateway())->toHaveCount(1);

    $facade->removeGateway($store->id(), $gateway->id());
    $storeWithNoGateways = $facade->findById($store->id());

    expect($storeWithNoGateways->gateway())->toHaveCount(0);
});

test('should find store by code', function () {
    $store = StoreContext::create()();

    $repositoryMock = Mockery::mock(StoreRepositoryInterface::class);
    $repositoryMock
        ->shouldReceive('findByCode')
        ->once()
        ->with($store->code())
        ->andReturn($store);

    $this->getContainer()->set(StoreRepositoryInterface::class, $repositoryMock);

    /** @var StoreFacade $facade */
    $facade = $this->getContainer()->get(StoreFacade::class);

    expect($facade->findByCode($store->code()))->not->toBeNull();
});

test('store should successfully add user', function () {
    $store = StoreContext::create()();
    $user = UserContext::create()();

    $store->addUser($user);

    /** @var EntityManagerInterface $entityManager */
    $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

    $entityManager->persist($store);
    $entityManager->persist($user);

    $entityManager->flush();

    /** @var StoreFacade $facade */
    $facade = $this->getContainer()->get(StoreFacade::class);

    $store = $facade->findById($store->id());

    expect($store->users())->toHaveCount(1);
});

test('should do nothing if try to remove not attached user', function () {
    $store = StoreContext::create()();
    $user = UserContext::create()();

    $store->removeUser($user);

    expect($store->users())->toHaveCount(0);
});

test('store should successfully remove user', function () {
    $store = StoreContext::create()();
    $user = UserContext::create()();

    $store->addUser($user);

    /** @var EntityManagerInterface $entityManager */
    $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

    $entityManager->persist($store);
    $entityManager->persist($user);

    $entityManager->flush();

    /** @var StoreFacade $facade */
    $facade = $this->getContainer()->get(StoreFacade::class);

    expect($store->users())->toHaveCount(1);

    $store = $facade->findById($store->id());
    $store->removeUser($user);

    $dbStore = $facade->findById($store->id());

    expect($dbStore->users())->toHaveCount(0);
});
