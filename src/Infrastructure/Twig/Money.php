<?php

declare(strict_types = 1);

namespace App\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class Money extends AbstractExtension
{
    public function __construct()
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('money', [$this, 'money']),
        ];
    }

    public function money(int $amount): string
    {
        return number_format($amount, 2, '.', ' ');
    }
}
