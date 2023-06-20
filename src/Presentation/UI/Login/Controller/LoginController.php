<?php

declare(strict_types=1);

namespace App\Presentation\UI\Login\Controller;

use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse;
use App\Infrastructure\Security\SecurityUtility;
use App\Presentation\UI\Login\Controller\Response\LoginResponse;
use App\Presentation\UI\Login\Form\LoginType;

#[Route(path: '/authentication/login', name: 'login')]
final class LoginController extends AbstractController
{
    public function __construct(private readonly SecurityUtility $securityUtility)
    {
    }

    public function __invoke(): HTMLResponse
    {
        $loginForm = $this->createForm(LoginType::class);

        if ('' !== $this->securityUtility->getLastUsername()) {
            $loginForm->get('email')->setData($this->securityUtility->getLastUsername());
        }

        $view = $this->renderTemplate('@login/login.html.twig', [
            'form' => $loginForm,
            'error' => $this->securityUtility->getLastAuthenticationError(),
        ]);

        return new LoginResponse($view);
    }
}
