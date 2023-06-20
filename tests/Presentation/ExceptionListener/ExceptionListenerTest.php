<?php

declare(strict_types = 1);

namespace App\Tests\Presentation\ExceptionListener;

use App\Infrastructure\HTTP\ErrorResponse;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Presentation\ExceptionListener\ExceptionHandler\ChainRunner;
use App\Presentation\ExceptionListener\ExceptionListener;
use Mockery;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class ExceptionListenerTest extends AbstractUnitTestCase
{
    public function testShouldGetDefaultErrorResponse(): void
    {
        $chainRunner = Mockery::mock(ChainRunner::class);
        $chainRunner
            ->shouldReceive('run')
            ->andThrow(new RuntimeException('This a mock error message', 100))
        ;

        $exceptionListener = new ExceptionListener($chainRunner);

        $event = new ExceptionEvent(
            Mockery::mock(HttpKernelInterface::class),
            Mockery::mock(Request::class),
            1,
            new RuntimeException()
        );

        $exceptionListener->onKernelException($event);

        $this->assertInstanceOf(ErrorResponse::class, $event->getResponse());
        $this->assertEquals('application/json', $event->getResponse()->headers->get('content-type'));
        $this->assertJson($event->getResponse()->getContent());
        $this->assertEquals(500, $event->getResponse()->getStatusCode());

        $body = json_decode($event->getResponse()->getContent(), true);

        $this->assertArrayHasKey('code', $body);
        $this->assertArrayHasKey('message', $body);
        $this->assertEquals(100, $body['code']);
        $this->assertEquals('This a mock error message', $body['message']);
    }
}
