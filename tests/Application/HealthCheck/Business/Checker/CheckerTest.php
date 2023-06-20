<?php

declare(strict_types=1);

namespace Application\HealthCheck\Business\Checker;

use App\Application\HealthCheck\Business\Checker\Checker;
use App\Application\HealthCheck\Business\Checker\HealthCheckerPluginInterface;
use App\Infrastructure\Test\AbstractUnitTestCase;
use Mockery;
use App\Application\HealthCheck\Business\Checker\Response;

final class CheckerTest extends AbstractUnitTestCase
{
    public function testShouldReturnEmptyArrayOfResults(): void
    {
        $checker = new Checker([]);

        $this->assertCount(0, $checker->check());
    }

    public function testShouldReturnOneElementInArrayOfResult(): void
    {
        $plugin = Mockery::mock(HealthCheckerPluginInterface::class);
        $plugin
            ->shouldReceive('check')
            ->andReturn(new Response('name', true, 'message'));

        $result = (new Checker([$plugin]))->check();

        $this->assertCount(1, $result);
        $this->assertInstanceOf(Response::class, $result[0]);
    }
}
