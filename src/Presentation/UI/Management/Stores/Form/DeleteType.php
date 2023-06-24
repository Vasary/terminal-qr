<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Stores\Form;

use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\HiddenType;
use App\Infrastructure\Form\SubmitType;

final class DeleteType extends AbstractType
{
    public function declareTypes(): array
    {
        return [
            [
                'type' => 'id',
                'class' => HiddenType::class,
                'options' => [],
            ],
            [
                'type' => 'submit',
                'class' => SubmitType::class,
                'options' => [
                    'label' => 'Confirm',
                    'attr' => [
                        'class' => 'btn btn-danger',
                    ],

                ],
            ],
            [
                'type' => 'cancel',
                'class' => SubmitType::class,
                'options' => [
                    'label' => 'Cancel',
                    'attr' => [
                        'class' => 'btn btn-warning',
                    ],
                ],
            ],
        ];
    }

    public function getOptions(): array
    {
        return [
            'csrf_protection' => true,
            'csrf_field_name' => 'store_delete',
            'csrf_token_id' => 'store_delete',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ];
    }
}
