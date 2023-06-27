<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Terminal\Controller;

use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Login\Terminal\Response\TerminalResponse;

#[Route(path: '/{code}', name: 'terminal')]
final class TerminalController extends AbstractController
{
    public function __invoke(): TerminalResponse
    {
        $view = $this->renderTemplate('@terminal/terminal.html.twig');

        return new TerminalResponse($view);
    }
}
