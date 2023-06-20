<?php

declare(strict_types=1);

namespace App\Tests\Presentation\HealthCheck;

use App\Application\HealthCheck\Business\Checker\CheckerInterface;
use App\Application\HealthCheck\Business\Checker\Response;
use App\Infrastructure\Test\AbstractUnitTestCase;
use Mockery;

final class HealthCheckControllerTest extends AbstractUnitTestCase
{
    public function testShouldSuccessfullyRetrieveOkResponse(): void
    {
        $checkerMock = Mockery::mock(CheckerInterface::class);
        $checkerMock
            ->shouldReceive('check')
            ->once()
            ->andReturn([new Response('Mock checker', true, 'ok')]);

        $client = self::createClient();
        $client->getContainer()->set(CheckerInterface::class, $checkerMock);

        $client->request('GET', '/health/check');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testShouldSuccessfullyRetrieveErrorResponse(): void
    {
        $checkerMock = Mockery::mock(CheckerInterface::class);
        $checkerMock
            ->shouldReceive('check')
            ->once()
            ->andReturn([new Response('Mock checker', false, 'ok')]);

        $client = self::createClient();
        $client->getContainer()->set(CheckerInterface::class, $checkerMock);

        $client->request('GET', '/health/check');

        $response = $client->getResponse();

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}
