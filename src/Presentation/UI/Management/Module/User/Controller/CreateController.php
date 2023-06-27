<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Controller;

use App\Application\User\Business\UserFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Module\User\Form\CreateType;
use App\Presentation\UI\Management\Module\User\Form\Data;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\UserCreate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/management/user', name: 'management_user_create', methods: ['GET', 'POST'])]
final class CreateController extends AbstractController
{
    private const PAGE_TITLE = 'Create new user';

    public function __construct(private readonly UserFacadeInterface $userFacade)
    {
    }

    public function __invoke(Request $request): Response
    {
        $this->isAccessGranted();

        $data = new Data();
        $form = $this->createForm(CreateType::class, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userFacade->create(UserCreate::fromArray($data->toArray()));

            return $this->redirectToRoute('management_users');
        }

        $view = $this->renderTemplate('@management/form.html.twig', [
            'form' => $form,
            'title' => self::PAGE_TITLE,
        ]);

        return new HTMLResponse($view);
    }
}
