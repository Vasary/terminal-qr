<?php

declare(strict_types = 1);

namespace App\Application\HealthCheck\Communication\Plugins;

use App\Application\Contract\ContainerInterface;
use App\Application\HealthCheck\Business\Checker\HealthCheckerPluginInterface;
use App\Application\HealthCheck\Business\Checker\Response;
use Throwable;

final class DoctrineConnectionCheckerPlugin implements HealthCheckerPluginInterface
{
    private const CHECK_RESULT_NAME = 'doctrine';
    private const CHECK_RESULT_OK = 'ok';

    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function check(): Response
    {
        if (false === $this->container->has('doctrine.orm.entity_manager')) {
            return new Response(self::CHECK_RESULT_NAME, false, 'Entity Manager Not Found.');
        }

        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        try {
            $connection = $entityManager->getConnection();
            $connection->executeQuery($connection->getDatabasePlatform()->getDummySelectSQL())->free();
        } catch (Throwable $exception) {
            return new Response(self::CHECK_RESULT_NAME, false, $exception->getMessage());
        }

        return new Response(self::CHECK_RESULT_NAME, true, self::CHECK_RESULT_OK);
    }
}
