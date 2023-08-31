<?php

declare(strict_types = 1);
//
//test('should retrieve all payments', function () {
//    $this->loadFixtures(new PaymentFixtures(10));
//
//    /** @var PaymentFacade $facade */
//    $facade = $this->getContainer()->get(PaymentFacade::class);
//
//    $stores = array_map(
//        fn(Store $store) => (string) $store->id(),
//        iterator_to_array($this->getContainer()->get(StoreFacade::class)->find())
//    );
//
//    $searchCriteria = SearchCriteria::fromArray(
//        [
//            'fields' => [],
//            'orderBy' => [
//                [
//                    'field' => 'createdAt',
//                    'direction' => 'desc',
//                ],
//            ],
//            'page' => 1,
//            'limit' => 5,
//            'stores' => $stores,
//        ]
//    );
//
//    $result = $facade->findByCriteria($searchCriteria);
//
//    expect($result->aggregate())->toHaveCount(10);
//    expect($result->pages())->toEqual(2);
//    expect($result->items())->toEqual(10);
//});
//test('should find one payment with q r', function () {
//    /** @var EntityManagerInterface $entityManager */
//    $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
//
//    $payment = PaymentContext::create()();
//
//    $entityManager->persist($payment->store());
//    $entityManager->persist($payment->gateway());
//
//    $payment->withQR(new QR('http://localhost/payload.jpg', 'http://localhost/qr.jpg'));
//
//    $entityManager->persist($payment);
//    $entityManager->flush();
//
//    /** @var PaymentFacade $facade */
//    $facade = $this->getContainer()->get(PaymentFacade::class);
//
//    $dbPayment = $facade->findById($payment->id());
//
//    expect($dbPayment->id())->toEqual($payment->id())
//        ->and($dbPayment->commission())->toEqual($payment->commission())
//        ->and($dbPayment->amount())->toEqual($payment->amount())
//        ->and($dbPayment->status())->toEqual($payment->status())
//        ->and($dbPayment->store())->toEqual($payment->store())
//        ->and($dbPayment->gateway())->toEqual($payment->gateway())
//        ->and($dbPayment->qr())->toEqual($payment->qr())
//        ->and($dbPayment->callback())->toEqual($payment->callback())
//        ->and($dbPayment->qr()->id())->toBeInstanceOf(Id::class)
//        ->and($dbPayment->qr()->createdAt())->toBeInstanceOf(DateTimeImmutable::class)
//        ->and($dbPayment->qr()->payload())->toEqual('http://localhost/payload.jpg')
//        ->and($dbPayment->qr()->image())->toEqual('http://localhost/qr.jpg');
//});
//test('payment should successfully change status', function () {
//    /** @var EntityManagerInterface $entityManager */
//    $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
//
//    $guzzleClientMock = new Client(['handler' => new MockHandler([
//        new TokenResponseMock(),
//    ])]);
//
//    $this->getContainer()->set(Client::class, $guzzleClientMock);
//
//    $payment = PaymentContext::create()();
//    $payment->withQR(new QR('http://localhost/payload.jpg', 'http://localhost/qr.jpg'));
//
//    $entityManager->persist($payment->store());
//    $entityManager->persist($payment->gateway());
//
//    $entityManager->persist($payment);
//    $entityManager->flush();
//
//    /** @var PaymentFacade $facade */
//    $facade = $this->getContainer()->get(PaymentFacade::class);
//
//    $payment = $facade->handle($payment->id());
//
//    expect($payment->status())->toEqual(PaymentStatusEnum::token);
//});
//
//test('payment should get new logs', function () {
//    /** @var EntityManagerInterface $entityManager */
//    $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
//
//    $payment = PaymentContext::create()();
//    $payment->withQR(new QR('http://localhost/payload.jpg', 'http://localhost/qr.jpg'));
//    $payment->addLog('Hello world');
//
//    $entityManager->persist($payment->store());
//    $entityManager->persist($payment->gateway());
//
//    $entityManager->persist($payment);
//    $entityManager->flush();
//
//    /** @var PaymentFacade $facade */
//    $facade = $this->getContainer()->get(PaymentFacade::class);
//
//    $dbPayment = $facade->findById($payment->id());
//
//    expect($dbPayment)->not->toBeNull();
//    expect($dbPayment->logs())->toHaveCount(1);
//    expect($dbPayment->logs()[0]->value())->toEqual('Hello world');
//});
//test('should successfully create new payment', function () {
//    $storeContext = StoreContext::create();
//    $gatewayContext = GatewayContext::create();
//
//    $gateway = $gatewayContext();
//    $store = $storeContext();
//
//    $store->addGateway($gateway);
//
//    /** @var EntityManagerInterface $entityManager */
//    $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
//
//    $entityManager->persist($store);
//    $entityManager->flush();
//
//    /** @var PaymentFacade $facade */
//    $facade = $this->getContainer()->get(PaymentFacade::class);
//
//    $payment = $facade->create(100, $store->id(), $gateway->id());
//
//    expect($payment->amount())->toEqual(100)
//        ->and($payment->store()->id())->toEqual($store->id())
//        ->and($payment->gateway()->id())->toEqual($gateway->id())
//        ->and($payment->status())->toEqual(PaymentStatusEnum::new)
//        ->and($payment->callback())->toEqual($gateway->callback())
//        ->and($payment->token())->toBeNull()
//        ->and($payment->qr())->toBeNull()
//        ->and($payment->logs())->toHaveCount(1)
//        ->and($payment->currency())->toEqual($gateway->currency())
//        ->and($payment->createdAt())->toBeInstanceOf(DateTimeImmutable::class)
//        ->and($payment->updatedAt())->toBeInstanceOf(DateTimeImmutable::class);
//});
