<?php

declare(strict_types=1);

namespace App\Presentation\UI\Management\Module\User\Controller;

use App\Application\User\Business\UserFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Response\HTMLResponse;

#[Route(path: '/management/users', name: 'management_users', methods: ['GET'])]
final class UsersController extends AbstractController
{
    public function __construct(private readonly UserFacadeInterface $facade)
    {
    }

    public function __invoke(): HTMLResponse
    {
        $this->isAccessGranted();

        $view = $this->renderTemplate('@user/users.html.twig', [
            'users' => $this->facade->find(),
        ]);

        return new HTMLResponse($view);
    }
}
