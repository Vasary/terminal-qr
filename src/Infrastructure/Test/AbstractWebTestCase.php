<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as Base;

abstract class AbstractWebTestCase extends Base
{
    use CleanModelContextTrait;

    protected function tearDown(): void
    {
        $this->cleanModelsContexts();
        Mockery::close();

        parent::tearDown();
    }

    public function save(...$models): void
    {
        /** @var EntityManagerInterface $manager */
        $manager = $this->getContainer()->get(EntityManagerInterface::class);

        foreach ($models as $model) {
            $manager->persist($model);
        }

        $manager->flush();
    }

    protected function loadFixtures(FixtureInterface $fixture): void
    {
        $loader = new Loader();
        $loader->addFixture($fixture);

        $executor = new ORMExecutor($this->getContainer()->get(EntityManagerInterface::class), new ORMPurger());
        $executor->execute($loader->getFixtures());
    }
}
