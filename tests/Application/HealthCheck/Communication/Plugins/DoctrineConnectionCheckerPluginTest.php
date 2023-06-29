<?php

declare(strict_types=1);

namespace App\Tests\Application\HealthCheck\Communication\Plugins;

use App\Application\Contract\ContainerInterface;
use App\Application\HealthCheck\Communication\Plugins\DoctrineConnectionCheckerPlugin;
use App\Infrastructure\Test\AbstractUnitTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Result;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use App\Application\HealthCheck\Business\Checker\Response;
use RuntimeException;

final class DoctrineConnectionCheckerPluginTest extends AbstractUnitTestCase
{
    public function testShouldFailsIfEntityManagerIsNotExisting(): void
    {
        $containerMock = Mockery::mock(ContainerInterface::class);
        $containerMock
            ->shouldReceive('has')
            ->with('doctrine.orm.entity_manager')
            ->andReturnFalse();

        $plugin = new DoctrineConnectionCheckerPlugin($containerMock);

        $response = $plugin->check();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertFalse($response->result());
        $this->assertEquals('doctrine', $response->name());
        $this->assertEquals('Entity Manager not found', $response->message());
    }

    public function testShouldFailsIfEntityManagerCanNotConnectToDataBase(): void
    {
        $containerMock = Mockery::mock(ContainerInterface::class);
        $containerMock
            ->shouldReceive('has')
            ->with('doctrine.orm.entity_manager')
            ->andReturnTrue();

        $entityManagerMock = Mockery::mock(EntityManagerInterface::class);
        $entityManagerMock
            ->shouldReceive('getConnection')
            ->andThrow(new RuntimeException('message'))
        ;

        $containerMock
            ->shouldReceive('get')
            ->with('doctrine.orm.entity_manager')
            ->andReturn($entityManagerMock);

        $plugin = new DoctrineConnectionCheckerPlugin($containerMock);

        $response = $plugin->check();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertFalse($response->result());
        $this->assertEquals('doctrine', $response->name());
        $this->assertEquals('message', $response->message());
    }
    public function testShouldReturnSuccessfulResult(): void
    {
        $containerMock = Mockery::mock(ContainerInterface::class);
        $containerMock
            ->shouldReceive('has')
            ->with('doctrine.orm.entity_manager')
            ->andReturnTrue();

        $resultMock = Mockery::mock(Result::class);
        $resultMock->shouldReceive('free');

        $connectionMock = Mockery::mock(Connection::class);
        $connectionMock
            ->shouldReceive('executeQuery')
            ->andReturn($resultMock)
        ;

        $platformMock = Mockery::mock(AbstractPlatform::class);
        $platformMock
            ->shouldReceive('getDummySelectSQL')
            ->andReturn('select * from table');

        $connectionMock->shouldReceive('getDatabasePlatform')->andReturn($platformMock);

        $entityManagerMock = Mockery::mock(EntityManagerInterface::class);
        $entityManagerMock
            ->shouldReceive('getConnection')
            ->andReturn($connectionMock)
        ;

        $containerMock
            ->shouldReceive('get')
            ->with('doctrine.orm.entity_manager')
            ->andReturn($entityManagerMock);

        $plugin = new DoctrineConnectionCheckerPlugin($containerMock);

        $response = $plugin->check();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->result());
        $this->assertEquals('doctrine', $response->name());
        $this->assertEquals('ok', $response->message());
    }
}
