<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Form;

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
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ];
    }
}
