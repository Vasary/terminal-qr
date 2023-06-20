<?php

declare(strict_types = 1);

namespace App\Infrastructure\Container;

use App\Application\Contract\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as SymfonyContainer;

final readonly class Container implements ContainerInterface
{
    public function __construct(private SymfonyContainer $container)
    {
    }

    public function has(string $container): bool
    {
        return $this->container->has($container);
    }

    public function get(string $container): ?object
    {
        return $this->container->get($container);
    }
}
