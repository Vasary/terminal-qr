<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Stores\Form;

use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\HiddenType;
use App\Infrastructure\Form\SubmitType;

final class DeleteType extends AbstractType
{
    public const BUTTON_SUBMIT = 'submit';
    public const BUTTON_CANCEL = 'cancel';

    public function declareTypes(): array
    {
        return [
            [
                'type' => 'id',
                'class' => HiddenType::class,
                'options' => [],
            ],
            [
                'type' => self::BUTTON_SUBMIT,
                'class' => SubmitType::class,
                'options' => [
                    'label' => 'Confirm',
                    'attr' => [
                        'class' => 'btn btn-danger',
                    ],

                ],
            ],
            [
                'type' => self::BUTTON_CANCEL,
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
