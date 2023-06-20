<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

abstract class HTMLResponse extends SymfonyResponse
{
    public function __construct(string $view)
    {
        parent::__construct($view, Response::HTTP_OK);
    }
}
