<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Context;

use DateTimeImmutable;

trait TimestampTrait
{
    public string $createdAt = '2022-01-01T00:00:00+00:00';
    public string $updatedAt = '2022-01-01T00:00:00+00:00';

    public function setTimestamps(object $model): static
    {
        $this
            ->setProperty($model, 'createdAt', new DateTimeImmutable($this->createdAt))
            ->setProperty($model, 'updatedAt', new DateTimeImmutable($this->updatedAt))
        ;

        return $this;
    }
}
