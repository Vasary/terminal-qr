<?php

declare(strict_types = 1);

namespace App\Tests\Presentation\HealthCheck;

use App\Application\HealthCheck\Business\Checker\CheckerInterface;
use App\Application\HealthCheck\Business\Checker\Response;
use Mockery;

test('Should successfully get successfully response', function () {
    $checkerMock = Mockery::mock(CheckerInterface::class);
    $checkerMock
        ->shouldReceive('check')
        ->once()
        ->andReturn([new Response('Mock checker', true, 'ok')]);

    $client = self::createClient();
    $client->getContainer()->set(CheckerInterface::class, $checkerMock);

    $client->request('GET', '/health/check');

    $response = $client->getResponse();

    $content = json_decode($response->getContent(), true);
    $healthCheckResponse = $content[0];

    expect($response->getStatusCode())->toEqual(200)
        ->and($response->getContent())->toBeJson()
        ->and($content)->toBeArray()
        ->and($healthCheckResponse)->toHaveKeys(['service', 'result', 'message'])
        ->and($healthCheckResponse['result'])->toBeTrue()
    ;
});

//test('Should get fail response', function () {
//    $checkerMock = Mockery::mock(CheckerInterface::class);
//    $checkerMock
//        ->shouldReceive('check')
//        ->once()
//        ->andReturn([new Response('Mock checker', false, 'ok')]);
//
//    $client = static::createClient();
//    $client->getContainer()->set(CheckerInterface::class, $checkerMock);
//
//    $client->request('GET', '/health/check');
//
//    $response = $client->getResponse();
//
//    $content = json_decode($response->getContent(), true);
//    $healthCheckResponse = $content[0];
//
//    expect($response->getStatusCode())->toEqual(500)
//        ->and($response->getContent())->toBeJson()
//        ->and($content)->toBeArray()
//        ->and($healthCheckResponse)->toHaveKeys(['service', 'result', 'message'])
//        ->and($healthCheckResponse['result'])->toBeFalse()
//    ;
//});
