<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Form;

use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\SubmitType;
use App\Infrastructure\Form\TextType;
use App\Infrastructure\Form\ChoiceType;

final class CreateType extends AbstractType
{
    public function declareTypes(): array
    {
        return [
            [
                'type' => 'title',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'gateways.attribute.title',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'gateways.attribute.title',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'host',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'gateways.attribute.host',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'gateways.attribute.host',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'portal',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'gateways.attribute.portal',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'gateways.attribute.portal',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'currency',
                'class' => ChoiceType::class,
                'options' => [
                    'required' => true,
                    'label' => 'gateways.attribute.currency',
                    'trim' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => ['RUB' => 'RUB'],
                ],
            ],
            [
                'type' => 'callback',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'gateways.attribute.callback.url',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'gateways.attribute.callback.url',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'submit',
                'class' => SubmitType::class,
                'options' => [
                    'label' => 'gateways.button.create',
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
            'csrf_field_name' => 'gateway_create',
            'csrf_token_id' => 'gateway_create',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
            'data_class' => Data::class,
        ];
    }
}
