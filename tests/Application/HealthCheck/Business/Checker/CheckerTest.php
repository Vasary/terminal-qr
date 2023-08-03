<?php

declare(strict_types = 1);

namespace Application\HealthCheck\Business\Checker;

use App\Application\HealthCheck\Business\Checker\Checker;
use App\Application\HealthCheck\Business\Checker\HealthCheckerPluginInterface;
use App\Application\HealthCheck\Business\Checker\Response;
use Mockery;

it('should return an empty array', function () {
    $checker = new Checker([]);

    expect($checker->check())->toHaveCount(0);
});

it('should return any one element in array', function () {
    $plugin = Mockery::mock(HealthCheckerPluginInterface::class);
    $plugin
        ->shouldReceive('check')
        ->andReturn(new Response('name', true, 'message'));

    $result = (new Checker([$plugin]))->check();

    expect($result)->toHaveCount(1)->and(current($result))->toBeInstanceOf(Response::class);
});
