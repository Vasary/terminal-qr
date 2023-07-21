<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test\Guzzle\Mock;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

final class TokenResponseMock extends Response
{
    private const HEADERS = [
        'Content-Type' => 'application/json',
    ];

    public function __construct()
    {
        parent::__construct(200, self::HEADERS, new Stream(fopen(__DIR__ . '/../_data/token.json', 'r')));
    }
}
