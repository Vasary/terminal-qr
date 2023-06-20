<?php

declare(strict_types = 1);

namespace App\Domain\ValueObject;

use Stringable;

final readonly class Title implements Stringable
{
    public function __construct(private string $title)
    {
    }

    public function title(): string
    {
        return $this->code;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
