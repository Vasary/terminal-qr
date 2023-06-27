<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Controller;

use App\Application\User\Business\UserFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Module\Stores\Form\DeleteType;
use App\Presentation\UI\Management\Module\User\Form\Delete;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\UserDelete;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/management/users/delete/{id}', name: 'management_user_delete', methods: ['GET', 'POST'])]
final class DeleteController extends AbstractController
{
    private const PAGE_TITLE = 'Delete user: %s';

    public function __construct(private readonly UserFacadeInterface $facade)
    {
    }

    public function __invoke(Request $request): Response
    {
        $this->isAccessGranted();

        $user = $this->facade->findById(Id::fromString($request->get('id')));
        if (null === $user) {
            return $this->redirectToRoute('management_users');
        }

        $data = new Delete();
        $data->id = (string) $user->id();
        $form = $this->createForm(DeleteType::class, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get(DeleteType::BUTTON_CANCEL)->isClicked()) {
                return $this->redirectToRoute('management_users');
            }

            $this->facade->delete(UserDelete::fromArray([
                'id' => $data->id,
            ]));

            return $this->redirectToRoute('management_users');
        }

        return new HTMLResponse(
            $this->renderTemplate('@management/form-delete.html.twig', [
                'form' => $form,
                'title' => sprintf(self::PAGE_TITLE, $user->email()),
            ])
        );
    }
}
