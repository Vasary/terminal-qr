<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test;

use App\Infrastructure\Persistence\DataFixtures\StoreFixtures;
use App\Infrastructure\Test\Faker\Factory;
use App\Infrastructure\Test\Faker\Generator;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

abstract class AbstractUnitTestCase extends WebTestCase
{
    use CleanModelContextTrait;

    protected ?Generator $faker = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cleanModelsContexts();
        $this->faker = null;
        Mockery::close();
    }

    protected function loadFixtures(FixtureInterface $fixture): void
    {
        $loader = new Loader();
        $loader->addFixture($fixture);

        $executor = new ORMExecutor($this->getContainer()->get(EntityManagerInterface::class), new ORMPurger());
        $executor->execute($loader->getFixtures());
    }
}
