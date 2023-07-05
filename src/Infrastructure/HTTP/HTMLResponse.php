<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

abstract class HTMLResponse extends SymfonyResponse
{
    public function __construct(string $view, int $status = SymfonyResponse::HTTP_OK, array $headers = [])
    {
        parent::__construct($view, $status, $headers);
    }
}
