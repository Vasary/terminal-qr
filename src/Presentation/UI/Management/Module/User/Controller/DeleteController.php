<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Controller;

use App\Application\Contract\TranslatorInterface;
use App\Application\User\Business\UserFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse as BaseResponse;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Management\Module\Stores\Form\DeleteType;
use App\Presentation\UI\Management\Module\User\Form\Delete;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\UserDelete;

#[Route(path: '/management/users/delete/{id}', name: 'management_user_delete', methods: ['GET', 'POST'])]
final class DeleteController extends AbstractController
{
    private const PAGE_TITLE = 'users.page.title.delete';

    public function __construct(private readonly UserFacadeInterface $facade, private readonly TranslatorInterface $translator)
    {
    }

    public function __invoke(HttpRequest $requestStack): BaseResponse
    {
        $this->isAccessGrantedUnless('ROLE_ADMIN');

        $request = $requestStack->getRequest();
        $user = $this->facade->findById(Id::fromString($request->get('id')));
        if (null === $user) {
            return $this->redirectTo('management_users');
        }

        $data = new Delete();
        $data->id = (string) $user->id();
        $form = $this->createForm(DeleteType::class, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get(DeleteType::BUTTON_CANCEL)->isClicked()) {
                return $this->redirectTo('management_users');
            }

            $this->facade->delete(UserDelete::fromArray([
                'id' => $data->id,
            ]));

            return $this->redirectTo('management_users');
        }

        return new HTMLResponse(
            $this->renderTemplate('@management/form-delete.html.twig', [
                'form' => $form,
                'title' => sprintf('%s: %s', $this->translator->trans(self::PAGE_TITLE), $user->email()),
            ])
        );
    }
}
