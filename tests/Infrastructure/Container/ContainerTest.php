<?php

declare(strict_types = 1);

it('container wrapper should return service', function () {
    $service = new class {
    };

    $symfonyContainerMock = Mockery::mock(Symfony\Component\DependencyInjection\ContainerInterface::class);
    $symfonyContainerMock
        ->shouldReceive('get')
        ->once()
        ->with('service')
        ->andReturn($service)
    ;

    $container = new App\Infrastructure\Container\Container($symfonyContainerMock);

    $result = $container->get('service');

    expect($result)->toBe($service);
});


it('container wrapper should has service', function () {
    $symfonyContainerMock = Mockery::mock(Symfony\Component\DependencyInjection\ContainerInterface::class);
    $symfonyContainerMock
        ->shouldReceive('has')
        ->once()
        ->with('service')
        ->andReturn(true)
    ;

    $container = new App\Infrastructure\Container\Container($symfonyContainerMock);

    expect($container->has('service'))->toBeTrue();
});
