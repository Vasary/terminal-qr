<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Controller;

use App\Application\User\Business\UserFacadeInterface;
use App\Domain\Model\Store;
use App\Domain\Model\User;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Presentation\UI\Management\Module\User\Form\Data;
use App\Presentation\UI\Management\Module\User\Form\UpdateType;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\UserUpdate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/management/users/edit/{id}', name: 'management_users_edit', methods: ['GET', 'POST'])]
final class EditController extends AbstractController
{
    private const PAGE_TITLE = 'Update user: %s';

    public function __construct(private readonly UserFacadeInterface $userFacade)
    {
    }

    private function updateUser(Data $data, User $user): void
    {
        $this->userFacade->update(UserUpdate::fromArray(
            array_merge($data->toArray(), ['id' => (string) $user->id()])
        ));
    }

    public function __invoke(Request $request): Response
    {
        $this->isAccessGranted();

        $user = $this->userFacade->findById(Id::fromString($request->get('id')));

        $data = new Data();
        $data->email = (string) $user->email();
        $data->password = '';
        $data->roles = $user->roles()[0];

        foreach ($user->stores() as $store) {
            /** @var Store $store */
            $data->stores[] = (string) $store->id();
        }

        $form = $this->createForm(UpdateType::class, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateUser($data, $user);

            return $this->redirectToRoute('management_users');
        }

        $view = $this->renderTemplate('@management/form.html.twig', [
            'form' => $form,
            'title' => sprintf(self::PAGE_TITLE, $user->email()),
        ]);

        return new HTMLResponse($view);
    }
}
