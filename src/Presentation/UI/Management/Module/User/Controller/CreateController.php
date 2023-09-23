<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Controller;

use App\Application\Contract\TranslatorInterface;
use App\Application\User\Business\UserFacadeInterface;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse as BaseResponse;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Management\Module\User\Form\CreateType;
use App\Presentation\UI\Management\Module\User\Form\Data;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\UserCreate;

#[Route(path: '/management/user', name: 'management_user_create', methods: ['GET', 'POST'])]
final class CreateController extends AbstractController
{
    private const PAGE_TITLE = 'users.page.title.create';

    public function __construct(private readonly UserFacadeInterface $userFacade)
    {
    }

    public function __invoke(HttpRequest $requestStack): BaseResponse
    {
        $this->isAccessGrantedUnless('ROLE_ADMIN');

        $data = new Data();
        $form = $this->createForm(CreateType::class, $data);

        $form->handleRequest($requestStack->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userFacade->create(UserCreate::fromArray($data->toArray()));

            return $this->redirectTo('management_users');
        }

        $view = $this->renderTemplate('@management/form.html.twig', [
            'form' => $form,
            'title' => self::PAGE_TITLE,
        ]);

        return new HTMLResponse($view);
    }
}
