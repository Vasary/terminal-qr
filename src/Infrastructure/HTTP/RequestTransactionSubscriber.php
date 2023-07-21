<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP;

use App\Application\Shared\Contract\TransactionServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

final class RequestTransactionSubscriber implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
//            KernelEvents::CONTROLLER => ['startTransaction', 10],
//            KernelEvents::RESPONSE => ['commitTransaction', 10],
//            KernelEvents::EXCEPTION => ['rollbackTransaction', 11],
        ];
    }

    public function startTransaction(ControllerEvent $event): void
    {
//        $this->transactionService->start();
    }

    public function commitTransaction(): void
    {
//        $this->transactionService->commit();
    }

    public function rollbackTransaction(): void
    {
//        $this->transactionService->rollback();
    }
}
