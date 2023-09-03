<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Controller;

use App\Application\Contract\TranslatorInterface;
use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse as BaseResponse;
use App\Infrastructure\HTTP\HttpRequest;
use App\Presentation\UI\Management\Module\Gateway\Form\Data;
use App\Presentation\UI\Management\Module\Gateway\Form\UpdateType;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\GatewayUpdate;

#[Route(path: '/management/gateway/edit/{id}', name: 'management_gateway_edit', methods: ['GET', 'POST'])]
final class EditController extends AbstractController
{
    private const PAGE_TITLE = 'gateways.title.update';

    public function __construct(private readonly GatewayFacadeInterface $gatewayFacade, private readonly TranslatorInterface $translator)
    {
    }

    public function __invoke(HttpRequest $request): BaseResponse
    {
        $this->isAccessGrantedUnless('ROLE_ADMIN');
        $request = $request->getRequest();

        $id = $request->get('id');

        $gateway = $this->gatewayFacade->findById(Id::fromString($id));

        $data = new Data();
        $data->title = (string) $gateway->title();
        $data->callback = (string) $gateway->callback();
        $data->host = (string) $gateway->host();
        $data->portal = (string) $gateway->portal();
        $data->currency = (string) $gateway->currency();

        $form = $this->createForm(UpdateType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->gatewayFacade->update(GatewayUpdate::fromArray(
                array_merge($data->toArray(), ['id' => $id])
            ));

            return $this->redirectTo('management_gateways');
        }

        $view = $this->renderTemplate('@management/form.html.twig', [
            'form' => $form,
            'title' => $this->translator->trans(self::PAGE_TITLE) . ': ' . $gateway->title(),
        ]);

        return new HTMLResponse($view);
    }
}
