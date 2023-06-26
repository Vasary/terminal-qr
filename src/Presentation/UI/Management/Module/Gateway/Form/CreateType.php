<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Form;

use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\SubmitType;
use App\Infrastructure\Form\TextType;

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
                    'label' => 'Title',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'Title',
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
                    'label' => 'Host',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'Host',
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
                    'label' => 'Portal',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'Portal',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'currency',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'Currency',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'Portal',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'callback',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'Callback url',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'Callback url'
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
                    'label' => 'Create',
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
