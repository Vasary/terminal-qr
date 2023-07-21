<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Terminal\Form;

use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\HiddenType;
use App\Infrastructure\Form\SubmitType;
use App\Infrastructure\Form\TextType;

final class CreateType extends AbstractType
{
    public function declareTypes(): array
    {
        return [
            [
                'type' => 'amountMask',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'terminal.amount.input.placeholder',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'terminal.amount.input.placeholder',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'amount',
                'class' => HiddenType::class,
                'options' => [
                    'required' => true,
                    'trim' => true,
                ],
            ],
            [
                'type' => 'gateway',
                'class' => HiddenType::class,
                'options' => [
                    'required' => true,
                    'trim' => true,
                ],
            ],
            [
                'type' => 'store',
                'class' => HiddenType::class,
                'options' => [
                    'required' => true,
                    'trim' => true,
                ],
            ],
            [
                'type' => 'submit',
                'class' => SubmitType::class,
                'options' => [
                    'label' => 'terminal.button.submit',
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
            'csrf_field_name' => 'terminal',
            'csrf_token_id' => 'terminal',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
            'data_class' => Data::class,
        ];
    }
}
