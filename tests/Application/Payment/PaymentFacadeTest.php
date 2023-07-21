<?php

declare(strict_types = 1);

namespace App\Tests\Application\Payment;

use App\Application\Payment\Business\PaymentFacade;
use App\Application\Store\Business\StoreFacade;
use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Model\QR;
use App\Domain\Model\Store;
use App\Infrastructure\Persistence\DataFixtures\PaymentFixtures;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Infrastructure\Test\Context\Model\PaymentContext;
use App\Infrastructure\Test\Guzzle\Mock\TokenResponseMock;
use App\Shared\Transfer\SearchCriteria;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;

final class PaymentFacadeTest extends AbstractUnitTestCase
{
    public function testShouldRetrieveAllPayments(): void
    {
        $this->loadFixtures(new PaymentFixtures(10));

        /** @var PaymentFacade $facade */
        $facade = $this->getContainer()->get(PaymentFacade::class);

        $stores = array_map(
            fn (Store $store) => (string) $store->id(),
            iterator_to_array($this->getContainer()->get(StoreFacade::class)->find())
        );

        $searchCriteria = SearchCriteria::fromArray(
            [
                'fields' => [],
                'orderBy' => [
                    [
                        'field' => 'createdAt',
                        'direction' => 'desc',
                    ],
                ],
                'page' => 1,
                'limit' => 5,
                'stores' => $stores,
            ]
        );

        $result = $facade->findByCriteria($searchCriteria);

        $this->assertCount(10, $result->aggregate());
        $this->assertEquals(2, $result->pages());
        $this->assertEquals(10, $result->items());
    }

    public function testShouldFindOnePaymentWithQR(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $payment = PaymentContext::create()();

        $entityManager->persist($payment->store());
        $entityManager->persist($payment->gateway());

        $payment->withQR(new QR('http://localhost/payload.jpg', 'http://localhost/qr.jpg'));

        $entityManager->persist($payment);
        $entityManager->flush();

        /** @var PaymentFacade $facade */
        $facade = $this->getContainer()->get(PaymentFacade::class);

        $dbPayment = $facade->findById($payment->id());

        $this->assertEquals($payment->id(), $dbPayment->id());
        $this->assertEquals($payment->commission(), $dbPayment->commission());
        $this->assertEquals($payment->amount(), $dbPayment->amount());
        $this->assertEquals($payment->status(), $dbPayment->status());
        $this->assertEquals($payment->store(), $dbPayment->store());
        $this->assertEquals($payment->gateway(), $dbPayment->gateway());
        $this->assertEquals($payment->qr(), $dbPayment->qr());
        $this->assertEquals($payment->callback(), $dbPayment->callback());
    }

    public function testPaymentShouldSuccessfullyChangeStatus(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $guzzleClientMock = new Client(['handler' => new MockHandler([
            new TokenResponseMock(),
        ])]);

        $this->getContainer()->set(Client::class, $guzzleClientMock);

        $payment = PaymentContext::create()();
        $payment->withQR(new QR('http://localhost/payload.jpg', 'http://localhost/qr.jpg'));

        $entityManager->persist($payment->store());
        $entityManager->persist($payment->gateway());

        $entityManager->persist($payment);
        $entityManager->flush();

        /** @var PaymentFacade $facade */
        $facade = $this->getContainer()->get(PaymentFacade::class);

        $payment = $facade->handle($payment->id());

        $this->assertEquals(PaymentStatusEnum::token, $payment->status());
    }

    public function testPaymentShouldGetNewLogs(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $payment = PaymentContext::create()();
        $payment->withQR(new QR('http://localhost/payload.jpg', 'http://localhost/qr.jpg'));
        $payment->addLog('Hello world');

        $entityManager->persist($payment->store());
        $entityManager->persist($payment->gateway());

        $entityManager->persist($payment);
        $entityManager->flush();

        /** @var PaymentFacade $facade */
        $facade = $this->getContainer()->get(PaymentFacade::class);

        $dbPayment = $facade->findById($payment->id());

        $this->assertNotNull($dbPayment);
        $this->assertCount(1, $dbPayment->logs());
        $this->assertEquals('Hello world', $dbPayment->logs()[0]->value());
    }
}
