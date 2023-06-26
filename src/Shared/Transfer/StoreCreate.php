<?php

declare(strict_types = 1);

namespace App\Shared\Transfer;

final class StoreCreate
{
    use CreateFromTrait;

    public function __construct(
        private readonly string $title,
        private readonly string $description,

    ) {
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }
}
