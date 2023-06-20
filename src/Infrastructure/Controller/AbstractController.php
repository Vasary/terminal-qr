<?php

declare(strict_types = 1);

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyController;

abstract class AbstractController extends SymfonyController
{
    private const AUTHENTICATED_FULLY = 'IS_AUTHENTICATED_FULLY';

    protected function renderTemplate(string $view, array $parameters = []): string
    {
        return $this->renderView($view, $parameters);
    }

    protected function isAccessGranted(): void
    {
        $this->denyAccessUnlessGranted(self::AUTHENTICATED_FULLY);
    }
}
