<?php

declare(strict_types = 1);

use App\Application\Gateway\Business\GatewayFacade;
use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Gateway;
use App\Domain\Repository\GatewayRepositoryInterface;
use App\Domain\ValueObject\Credentials\Stub;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Uuid;
use App\Infrastructure\Persistence\DataFixtures\GatewayFixtures;
use App\Infrastructure\Test\Context\Model\GatewayContext;
use App\Infrastructure\Test\Context\Model\StoreContext;
use App\Infrastructure\Test\Stub\IdFactoryStub;
use App\Shared\Transfer\GatewayCreate;
use App\Shared\Transfer\GatewayDelete;
use App\Shared\Transfer\GatewayUpdate;
use App\Shared\Transfer\SearchCriteria;
use Doctrine\ORM\EntityManagerInterface;

describe('Gateway Facade', function () {
    it('should retrieve gateway by key', function () {
        $gateway = GatewayContext::create()();

        $repositoryMock = Mockery::mock(GatewayRepositoryInterface::class);
        $repositoryMock
            ->shouldReceive('findByKey')
            ->once()
            ->with($gateway->key())
            ->andReturn($gateway);

        $this->getContainer()->set(GatewayRepositoryInterface::class, $repositoryMock);

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        expect($facade->findByKey($gateway->key()))->not->toBeNull();
    });

    it('should successfully create a gateway with stub credential', function () {
        $transfer = GatewayCreate::fromArray([
            'title' => faker()->title(),
            'callback' => faker()->url(),
            'currency' => faker()->currencyCode(),
        ]);

        $credentials = [
            'type' => Stub::class,
            'login' => faker()->lexify('?????'),
            'password' => faker()->lexify('????????'),
        ];

        $idFactory = new IdFactoryStub(
            new Uuid('edff293a-02d4-4872-b193-c51f8305a953'),
            new Uuid('f5593d03-aa33-4405-b133-a772f33ec061'),
        );

        Id::setFactory($idFactory);

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $gateway = $facade->create($transfer, $credentials);

        /** @var Stub $gatewayCredential */
        $gatewayCredential = $gateway->credential();

        expect($gatewayCredential)->toBeInstanceOf(Stub::class)
            ->and($gatewayCredential->login())->toEqual($credentials['login'])
            ->and($gatewayCredential->password())->toEqual($credentials['password'])
            ->and((string) $gateway->title())->toEqual($transfer->title())
            ->and((string) $gateway->callback())->toEqual($transfer->callback())
            ->and((string) $gateway->key())->toEqual('edff293a-02d4-4872-b193-c51f8305a953')
            ->and($gateway->createdAt())->toBeInstanceOf(DateTimeImmutable::class)
            ->and($gateway->updatedAt())->toBeInstanceOf(DateTimeImmutable::class)
            ->and($idFactory->isEmpty())->toBeTrue()
        ;
    });

    it('should return ten records via facade', function () {
        $this->loadFixtures(new GatewayFixtures(10));

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        expect(iterator_to_array($facade->find()))->toHaveCount(10);
    });

    it('should successfully update gateway', function () {
        $gateway = GatewayContext::create()();

        $this->save($gateway);

        $transfer = GatewayUpdate::fromArray([
            'id' => (string) $gateway->id(),
            'title' => faker()->title(),
            'callback' => faker()->url(),
            'currency' => faker()->currencyCode(),
        ]);

        $credentials = [
            'type' => Stub::class,
            'login' => faker()->lexify('?????'),
            'password' => faker()->lexify('????????'),
        ];

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $updatedGateway = $facade->update($transfer, $credentials);
        $gatewayInDatabase = $facade->findById(Id::fromString($transfer->id()));

        /** @var Stub $updatedGatewayCredential */
        $updatedGatewayCredential = $updatedGateway->credential();

        /** @var Stub $gatewayInDatabaseCredential */
        $gatewayInDatabaseCredential = $gatewayInDatabase->credential();

        expect((string) $updatedGateway->id())->toEqual($transfer->id())
            ->and((string) $updatedGateway->title())->toEqual($transfer->title())
            ->and((string) $updatedGateway->callback())->toEqual($transfer->callback())
            ->and(iterator_to_array($facade->find()))->toHaveCount(1)
            ->and($gatewayInDatabase)->not->toBeNull()
            ->and((string) $gatewayInDatabase->title())->toEqual($transfer->title())
            ->and((string) $gatewayInDatabase->callback())->toEqual($transfer->callback())
            ->and($updatedGatewayCredential->login())->toEqual($credentials['login'])
            ->and($updatedGatewayCredential->password())->toEqual($credentials['password'])
            ->and($gatewayInDatabaseCredential->login())->toEqual($credentials['login'])
            ->and($gatewayInDatabaseCredential->password())->toEqual($credentials['password'])
        ;
    });

    it('should fails on update with not-existing gateway id', function () {
        $this->getContainer()->get(GatewayFacade::class)->update(
            GatewayUpdate::fromArray([
                'id' => faker()->uuid(),
                'title' => faker()->title(),
                'callback' => faker()->url(),
                'currency' => faker()->currencyCode(),
            ]),
            []
        );
    })->expectException(NotFoundException::class);

    it('should retrieve gateway', function () {
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

        expect(iterator_to_array($facade->find()))->toHaveCount(4)
            ->and($result->aggregate())->toHaveCount(1)
            ->and($result->pages())->toEqual(1)
            ->and($result->items())->toEqual(1);
    });

    it('should\'t return any gateways with not findable pattern ', function () {
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

        expect(iterator_to_array($facade->find()))->toHaveCount(3)
            ->and($result->aggregate())->toHaveCount(0)
            ->and($result->pages())->toEqual(0)
            ->and($result->items())->toEqual(0);
    });

    it('should retrieve gateways ordered by field desc', function (string $direction, string $sorter) {
        $this->loadFixtures(new GatewayFixtures(10));

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $searchCriteria = SearchCriteria::fromArray(
            [
                'fields' => [],
                'orderBy' => [
                    [
                        'field' => 'title',
                        'direction' => $direction,
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

        $sorter($expectedSorted);

        expect(iterator_to_array($facade->find()))->toHaveCount(10)
            ->and($result->aggregate())->toHaveCount(10)
            ->and($result->pages())->toEqual(1)
            ->and($result->items())->toEqual(10)
            ->and($titles)->toBe($expectedSorted);
    })->with([
        ['desc', 'arsort'],
        ['asc', 'asort'],
    ]);

    it('should successfully delete delete gateway', function () {
        $this->loadFixtures(new GatewayFixtures(10));

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        /** @var Gateway $randomGateway */
        $randomGateway = iterator_to_array($facade->find())[0];

        $facade->delete(new GatewayDelete((string) $randomGateway->id()));

        $result = $facade->findById($randomGateway->id());

        expect($result)->toBeNull()->and(iterator_to_array($facade->find()))->toHaveCount(9);
    });

    it('should successfully add store', function () {
        $gateway = GatewayContext::create()();
        $store = StoreContext::create()();

        $gateway->addStore($store);

        $this->save($gateway, $store);

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $dbGateway = $facade->findById($gateway->id());

        expect($dbGateway->stores())->toHaveCount(1);
    });

    it('should not remove not bound store', function () {
        $gateway = GatewayContext::create()();
        $store = StoreContext::create()();

        $this->save($gateway, $store);

        /** @var GatewayFacade $facade */
        $facade = $this->getContainer()->get(GatewayFacade::class);

        $dbGateway = $facade->findById($gateway->id());

        expect($dbGateway)->not->toBeNull()->and($dbGateway->stores())->toHaveCount(0);

        $dbGateway->removeStore($store);

        expect($dbGateway->stores())->toHaveCount(0);
    });
});
