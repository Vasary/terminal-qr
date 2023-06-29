<?php

declare(strict_types = 1);

namespace App\Tests\Application\Payment;

use App\Application\Payment\Business\PaymentFacade;
use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Model\QR;
use App\Infrastructure\Persistence\DataFixtures\PaymentFixtures;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Infrastructure\Test\Context\Model\PaymentContext;
use App\Shared\Transfer\SearchCriteria;
use Doctrine\ORM\EntityManagerInterface;

final class PaymentFacadeTest extends AbstractUnitTestCase
{
    public function testShouldRetrieveAllPayments(): void
    {
        $this->loadFixtures(new PaymentFixtures(10));

        /** @var PaymentFacade $facade */
        $facade = $this->getContainer()->get(PaymentFacade::class);

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
            ]
        );

        $result = $facade->findByCriteria($searchCriteria);

        $this->assertCount(10, $result->aggregate());
        $this->assertEquals(2, $result->pages());
        $this->assertEquals(10, $result->items());
    }

    public function testShouldFindOnePaymentWithQR(): void
    {
        $payment = PaymentContext::create()();
        $payment->withQR(new QR('http://localhost/payload.jpg', 'http://localhost/qr.jpg'));

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

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
        $payment = PaymentContext::create()();
        $payment->withQR(new QR('http://localhost/payload.jpg', 'http://localhost/qr.jpg'));

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $entityManager->persist($payment);
        $entityManager->flush();

        /** @var PaymentFacade $facade */
        $facade = $this->getContainer()->get(PaymentFacade::class);

        $payment = $facade->changeStatus($payment, PaymentStatusEnum::FAILURE);

        $this->assertEquals(PaymentStatusEnum::FAILURE, $payment->status());
    }

    public function testPaymentShouldGetNewLogs(): void
    {
        $payment = PaymentContext::create()();
        $payment->withQR(new QR('http://localhost/payload.jpg', 'http://localhost/qr.jpg'));
        $payment->addLog('Hello world');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

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
