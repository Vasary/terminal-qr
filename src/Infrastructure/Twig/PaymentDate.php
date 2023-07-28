<?php

declare(strict_types = 1);

namespace App\Infrastructure\Twig;

use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class PaymentDate extends AbstractExtension
{
    public function __construct()
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('paymentDate', [$this, 'paymentDate']),
        ];
    }

    public function paymentDate(DateTimeInterface $dateTime): string
    {
        return date('Y-m-d H:i:s', $dateTime->getTimestamp());
    }
}
