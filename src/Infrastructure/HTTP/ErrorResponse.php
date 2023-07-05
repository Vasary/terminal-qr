<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP;

use Symfony\Component\HttpFoundation\Response;

final class ErrorResponse extends HTMLResponse
{
    public function __construct(string $view)
    {
        parent::__construct($view, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
