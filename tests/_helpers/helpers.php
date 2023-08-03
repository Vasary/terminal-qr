<?php

declare(strict_types = 1);

use App\Infrastructure\Test\Faker\Factory;
use App\Infrastructure\Test\Faker\Generator;
use App\Kernel;
use Doctrine\Migrations\MigratorConfiguration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\DependencyInjection\ContainerInterface;


if (!function_exists('faker')) {
    function faker(): Generator
    {
        static $faker;

        $faker ??= Factory::create();

        return $faker;
    }
}

function app(): Kernel
{
    static $kernel;

    $kernel ??= (function () {
        $env = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'test';
        $debug = $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? true;

        $kernel = new Kernel((string)$env, (bool)$debug);
        $kernel->boot();

        return $kernel;
    })();

    return $kernel;
}

function container(): ContainerInterface
{
    $container = app()->getContainer();

    return $container->has('test.service_container')
        ? $container->get('test.service_container')
        : $container;
}

function save(object ...$entities): void
{
    $manager = container()->get(EntityManagerInterface::class);
    foreach ($entities as $entity) {
        $manager->persist($entity);
        $manager->flush();
    }
}

function runMigrations(): void
{
    $dependencyFactory = container()->get('doctrine.migrations.dependency_factory');
    $dependencyFactory->getMetadataStorage()->ensureInitialized();
    $migratorConfiguration = new MigratorConfiguration();
    $planCalculator = $dependencyFactory->getMigrationPlanCalculator();
    $migrator = $dependencyFactory->getMigrator();
    $version = $dependencyFactory->getVersionAliasResolver()->resolveVersionAlias('latest');
    $plan = $planCalculator->getPlanUntilVersion($version);
    $migrator->migrate($plan, $migratorConfiguration);
}

function dropDatabase(): void
{
    $doctrine = container()->get('doctrine');
    $connection = $doctrine->getConnection($doctrine->getDefaultConnectionName());
    $params = $connection->getParams();

    if (file_exists($params['path'])) {
        unlink($params['path']);
    }
}

function createSchema(): void
{
    $entityManager = container()->get(EntityManagerInterface::class);
    $schemaTool = new SchemaTool($entityManager);
    $classes = $entityManager->getMetadataFactory()->getAllMetadata();
    $schemaTool->createSchema($classes);
}
