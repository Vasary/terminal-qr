<?php

declare(strict_types = 1);

namespace App\Application\Payment\Business\StateMachine;

use App\Domain\Enum\PaymentStatusEnum;
use App\Domain\Model\Payment;
use App\Infrastructure\StateMachine\MarkingStoreInterface;
use Symfony\Component\Workflow\Marking;

final class Marker implements MarkingStoreInterface
{
    public function getMarking(object $subject): Marking
    {
        /** @var Payment $subject */
        return new Marking([$subject->status()->name => 1]);
    }

    public function setMarking(object $subject, Marking $marking, array $context = []): void
    {
        /*** @var Payment $subject */
        $state = key($marking->getPlaces());

        $subject->withStatus(PaymentStatusEnum::fromName($state));
    }
}
