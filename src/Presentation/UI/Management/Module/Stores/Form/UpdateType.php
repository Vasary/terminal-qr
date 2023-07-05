<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Stores\Form;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Domain\Model\Gateway;
use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\ChoiceType;
use App\Infrastructure\Form\SubmitType;
use App\Infrastructure\Form\TextAreaType;
use App\Infrastructure\Form\TextType;

final class UpdateType extends AbstractType
{
    public function __construct(private readonly GatewayFacadeInterface $gatewayFacade) {}

    public function declareTypes(): array
    {
        return [
            [
                'type' => 'title',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'stores.form.title',
                    'attr' => [
                        'placeholder' => 'stores.form.title',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'gateways',
                'class' => ChoiceType::class,
                'options' => [
                    'required' => true,
                    'label' => 'stores.form.gateways',
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => $this->createChoiceList(),
                ],
            ],
            [
                'type' => 'description',
                'class' => TextAreaType::class,
                'options' => [
                    'required' => false,
                    'help' => 'stores.form.description.help',
                    'label' => ' ',
                    'attr' => [
                        'placeholder' => 'stores.form.description.placeholder',
                        'rows' => 4,
                        'cols' => 50,
                    ],
                    'row_attr' => [
                        'class' => 'mb-3',
                    ],
                ],
            ],
            [
                'type' => 'submit',
                'class' => SubmitType::class,
                'options' => [
                    'label' => 'stores.form.update',
                    'attr' => [
                        'class' => 'btn btn-primary w-100',
                    ],
                ],
            ],
        ];
    }

    public function getOptions(): array
    {
        return [
            'csrf_protection' => true,
            'csrf_field_name' => 'store_update',
            'csrf_token_id' => 'store_update',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
            'data_class' => Data::class,
        ];
    }

    private function createChoiceList(): array
    {
        $list = [];
        foreach ($this->gatewayFacade->find() as $gateway) {
            /** @var Gateway $gateway */
            $list[(string) $gateway->title()] = (string) $gateway->id();
        }

        return $list;
    }
}
