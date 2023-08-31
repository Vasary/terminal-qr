<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Id;
use DateTimeImmutable;

class QR
{
    private Id $id;
    private DateTimeImmutable $createdAt;

    public function __construct(
        private readonly string $payload,
        private readonly string $imageUrl,
        DateTimeImmutable $now,
    )
    {
        $this->id = Id::create();
        $this->createdAt = $now;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function payload(): string
    {
        return $this->payload;
    }

    public function image(): string
    {
        return $this->imageUrl;
    }
}
