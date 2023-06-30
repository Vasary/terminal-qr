<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Index\Controller;

use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Response\HTMLResponse;

#[Route(path: '/', name: 'index')]
final class IndexController extends AbstractController
{
    public function __construct()
    {
    }

    public function __invoke(): HTMLResponse
    {
        $view = $this->renderTemplate('@index/index.html.twig');

        return new HTMLResponse($view);
    }
}
