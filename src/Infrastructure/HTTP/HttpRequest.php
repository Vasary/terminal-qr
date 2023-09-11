<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class HttpRequest
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function getId(): string
    {
        return $this->requestStack->getCurrentRequest()->get('id');
    }
}
