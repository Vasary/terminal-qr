<?php

declare(strict_types = 1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Id;
use DateTimeImmutable;

class User
{
    private Id $id;
    private DateTimeImmutable $updatedAt;
    private readonly DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->id = Id::create();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }
}
