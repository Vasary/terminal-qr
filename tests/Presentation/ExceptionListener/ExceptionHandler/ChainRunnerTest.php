<?php

declare(strict_types = 1);

namespace App\Tests\Presentation\ExceptionListener\ExceptionHandler;

use App\Infrastructure\HTTP\ErrorResponse;
use App\Infrastructure\HTTP\Exception\ValidationException;
use App\Infrastructure\Test\AbstractUnitTestCase;
use App\Presentation\ExceptionListener\ExceptionHandler\ChainRunner;
use App\Presentation\ExceptionListener\ExceptionHandler\Handler\ValidationExceptionHandler;
use InvalidArgumentException;
use Mockery;
use RuntimeException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

final class ChainRunnerTest extends AbstractUnitTestCase
{
    public function testShouldThrowRuntimeException(): void
    {
        $this->expectException(RuntimeException::class);

        $chainHandler = new ChainRunner();
        $chainHandler->run(new InvalidArgumentException());
    }

    public function testShouldSuccessfullyObtainValidationExceptionResponse(): void
    {
        $chainHandler = new ChainRunner([
            new ValidationExceptionHandler(),
        ]);

        $constraint = Mockery::mock(ConstraintViolation::class);
        $constraint
            ->shouldReceive('getPropertyPath')
            ->once()
            ->andReturn('name')
        ;

        $constraint
            ->shouldReceive('getMessage')
            ->once()
            ->andReturn('Hello')
        ;

        $constraints = new ConstraintViolationList([$constraint]);

        $response = $chainHandler->run(new ValidationException($constraints));

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->assertJson($response->getContent());

        $body = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('code', $body);
        $this->assertArrayHasKey('message', $body);
        $this->assertArrayHasKey('constraints', $body);
        $this->assertEquals(400, $body['code']);
        $this->assertEquals('Validation fail', $body['message']);
        $this->assertCount(1, $body['constraints']);
        $this->assertEquals('name', $body['constraints'][0]['property']);
        $this->assertEquals('Hello', $body['constraints'][0]['message']);
    }
}
