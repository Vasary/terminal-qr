<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Form;

use App\Infrastructure\Form\SubmitType;

final class CreateType extends MutationType
{
    public function declareTypes(): array
    {
        return array_merge(
            parent::declareTypes(),
            [
                [
                    'type' => 'submit',
                    'class' => SubmitType::class,
                    'options' => [
                        'label' => 'users.button.create',
                        'attr' => [
                            'class' => 'btn btn-primary w-100',
                        ],
                    ],
                ],
            ],
        );
    }
}
