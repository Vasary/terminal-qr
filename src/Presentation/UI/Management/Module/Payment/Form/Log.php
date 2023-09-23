<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Payment\Form;

use App\Infrastructure\Form\Data\PropertyAccessorTrait;
use DateTimeImmutable;

final readonly class Log
{
    use PropertyAccessorTrait;

    public function __construct(private string $createdAt, private string $text)
    {
    }

    public function createdAt(): string
    {
        return $this->createdAt;
    }

    public function text(): string
    {
        return $this->text;
    }
}
