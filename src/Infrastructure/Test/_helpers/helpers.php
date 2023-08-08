<?php

declare(strict_types = 1);

use App\Infrastructure\Test\Faker\Factory;
use App\Infrastructure\Test\Faker\Generator;
use App\Kernel;
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

if (!function_exists('app')) {
    function app(): Kernel
    {
        static $kernel;

        $kernel ??= (function () {
            $kernel = new Kernel('test', true);
            $kernel->boot();

            return $kernel;
        })();

        return $kernel;
    }
}

if (!function_exists('container')) {
    function container(): ContainerInterface
    {
        $container = app()->getContainer();

        return $container->has('test.service_container')
            ? $container->get('test.service_container')
            : $container;
    }
}

if (!function_exists('updateSchema')) {
    function updateSchema(): void
    {
        $entityManager = container()->get(EntityManagerInterface::class);
        $schemaTool = new SchemaTool($entityManager);
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->updateSchema($classes);
    }
}

