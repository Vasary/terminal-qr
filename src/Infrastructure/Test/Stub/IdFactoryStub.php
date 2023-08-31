<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Stub;

use App\Domain\Factory\UUID\Builder\Builder;
use App\Domain\Factory\UUID\Generator\SimpleGenerator;
use App\Domain\Factory\UUID\IdFactory;
use App\Domain\Factory\UUID\IdFactoryInterface;
use App\Domain\ValueObject\Uuid;
use LogicException;
use RuntimeException;
use SplQueue;

final class IdFactoryStub implements IdFactoryInterface
{
    private SplQueue $queue;

    public function __construct(Uuid ...$ids)
    {
        $this->queue = new SplQueue();

        foreach ($ids as $id) {
            $this->queue->enqueue($id);
        }
    }

    public function v4(): Uuid
    {
        try {
            return $this->queue->dequeue();
        } catch (RuntimeException $exception) {
            throw new RuntimeException('Id queue is empty', 0, $exception);
        }
    }

    public function fromString(string $uuidString): Uuid
    {
        throw new LogicException('Create from string is now available for a stubs');
    }

    public function isEmpty(): bool
    {
        return 0 === $this->queue->count();
    }
}
