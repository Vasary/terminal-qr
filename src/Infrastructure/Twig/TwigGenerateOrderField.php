<?php

declare(strict_types = 1);

namespace App\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TwigGenerateOrderField extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('orderBy', [$this, 'orderBy']),
        ];
    }

    public function orderBy(string $field, string $direction, string $currentField, string $currentDirection): string
    {
        if ($field === $currentField) {
            if ($direction !== $currentDirection) {
                return $field.':'.$direction;
            } else {
                return 'desc' === $direction
                    ? $field.':asc'
                    : $field.':desc';
            }
        }

        return $field . ':' . $direction;
    }
}
