<?php

declare(strict_types=1);

namespace App\Presentation\UI\Login\Controller;

use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse;
use App\Presentation\UI\Login\Controller\Response\LogoutResponse;

#[Route(path: '/authentication/logout', name: 'logout')]
final class LogoutController extends AbstractController
{
    public function __construct()
    {
    }

    public function __invoke(): HTMLResponse
    {
        return new LogoutResponse();
    }
}
