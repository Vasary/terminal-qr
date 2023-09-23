<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Controller;

use App\Application\Contract\TranslatorInterface;
use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Domain\Model\Gateway;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Annotation\Route;
use App\Infrastructure\Controller\AbstractController;
use App\Infrastructure\HTTP\HTMLResponse as BaseResponse;
use App\Infrastructure\HTTP\HttpRequest;
use App\Infrastructure\Serializer\Serializer;
use App\Presentation\UI\Management\Module\Gateway\Controller\Trait\CredentialsExtractorTrait;
use App\Presentation\UI\Management\Module\Gateway\Form\Credential;
use App\Presentation\UI\Management\Module\Gateway\Form\Data;
use App\Presentation\UI\Management\Module\Gateway\Form\UpdateType;
use App\Presentation\UI\Management\Response\HTMLResponse;
use App\Shared\Transfer\GatewayUpdate;

#[Route(path: '/management/gateway/edit/{id}', name: 'management_gateway_edit', methods: ['GET', 'POST'])]
final class EditController extends AbstractController
{
    use CredentialsExtractorTrait;

    private const PAGE_TITLE = 'gateways.title.update';

    public function __construct(private readonly GatewayFacadeInterface $gatewayFacade, private readonly TranslatorInterface $translator)
    {
    }

    private function prepareTransfer(Data $data, Gateway $gateway): GatewayUpdate
    {
        return GatewayUpdate::fromArray(
            array_merge($data->toArray(), ['id' => (string) $gateway->id()])
        );
    }

    private function createData(Gateway $gateway): Data
    {
        $data = new Data();
        $data
            ->withTitle((string) $gateway->title())
            ->withCallback((string) $gateway->callback())
        ;

        $credentials = Serializer::create()->toArray($gateway->credential());
        foreach (array_keys($credentials) as $key) {
            $credential = new Credential();
            $credential
                ->withKey($key)
                ->withValue($credentials[$key])
            ;

            $data->withCredential($credential);
        }

        $data->withType($gateway->credential()::class);

        return $data;
    }

    private function createTitle(Gateway $gateway): string
    {
        return $this->translator->trans(self::PAGE_TITLE) . ': ' . $gateway->title();
    }

    public function __invoke(HttpRequest $request): BaseResponse
    {
        $this->isAccessGrantedUnless('ROLE_ADMIN');

        $gateway = $this->gatewayFacade->findById(Id::fromString($request->getId()));
        $data = $this->createData($gateway);

        $form = $this->createForm(UpdateType::class, $data);
        $form->handleRequest($request->getRequest());

        if ($form->isSubmitted()) {
//            dd($form->getErrors(true), $form->getViewData());
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->gatewayFacade->update($this->prepareTransfer($data, $gateway), $this->extractCredentials($data));

            return $this->redirectTo('management_gateways');
        }

        $view = $this->renderTemplate('@management/form.html.twig', [
            'form' => $form,
            'title' => $this->createTitle($gateway),
        ]);

        return new HTMLResponse($view);
    }
}
