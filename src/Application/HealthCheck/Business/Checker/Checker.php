<?php

declare(strict_types = 1);

namespace App\Application\HealthCheck\Business\Checker;

final readonly class Checker implements CheckerInterface
{
    public function __construct(private array $checkers = [])
    {
    }

    public function check(): array
    {
        $response = [];
        foreach ($this->checkers as $checker) {
            /** @var HealthCheckerPluginInterface $checker */
            $response[] = $checker->check();
        }

        return $response;
    }
}
