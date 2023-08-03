<?php

declare(strict_types = 1);

namespace App\Tests\Application\HealthCheck\Communication\Plugins;

use App\Application\Contract\ContainerInterface;
use App\Application\HealthCheck\Business\Checker\Response;
use App\Application\HealthCheck\Communication\Plugins\DoctrineConnectionCheckerPlugin;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Result;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use RuntimeException;

it('should fails if entity manager is not existing', function () {
    $containerMock = Mockery::mock(ContainerInterface::class);
    $containerMock
        ->shouldReceive('has')
        ->with('doctrine.orm.entity_manager')
        ->andReturnFalse();

    $plugin = new DoctrineConnectionCheckerPlugin($containerMock);

    $response = $plugin->check();

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->result())->toBeFalse()
        ->and($response->name())->toEqual('doctrine')
        ->and($response->message())->toEqual('Entity Manager not found')
    ;
});

it('should fails if entity manager can\'t Connect to database', function () {
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

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->result())->toBeFalse()
        ->and($response->name())->toEqual('doctrine')
        ->and($response->message())->toEqual('message')
    ;
});

it('should return successful result', function () {
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

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->result())->toBeTrue()
        ->and($response->name())->toEqual('doctrine')
        ->and($response->message())->toEqual('ok')
    ;
});
