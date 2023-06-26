<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Form;

use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\SubmitType;
use App\Infrastructure\Form\TextType;

final class UpdateType extends AbstractType
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
                    'attr' => [
                        'placeholder' => 'Callback url',
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
                    'label' => 'Update',
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
            'csrf_field_name' => 'gateway_update',
            'csrf_token_id' => 'gateway_update',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
            'data_class' => Data::class,
        ];
    }
}
