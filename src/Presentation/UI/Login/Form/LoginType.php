<?php

declare(strict_types=1);

namespace App\Presentation\UI\Login\Form;

use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\CheckboxType;
use App\Infrastructure\Form\EmailType;
use App\Infrastructure\Form\PasswordType;
use App\Infrastructure\Form\SubmitType;

final class LoginType extends AbstractType
{
    public function declareTypes(): array
    {
        return [
            [
                'type' => 'email',
                'class' => EmailType::class,
                'options' => [
                    'required' => false,
                    'label' => 'Email',
                    'attr' => [
                        'placeholder' => 'Email',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating',
                    ],
                ],
            ],
            [
                'type' => 'password',
                'class' => PasswordType::class,
                'options' => [
                    'required' => false,
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Password',

                    ],
                    'row_attr' => [
                        'class' => 'form-floating',
                    ],
                ],
            ],
            [
                'type' => 'remember',
                'class' => CheckboxType::class,
                'options' => [
                    'label' => 'Remember me',
                ],
            ],
            [
                'type' => 'submit',
                'class' => SubmitType::class,
                'options' => [
                    'label' => 'Sign in',
                    'row_attr' => [
                        'class' => 'mb-4',
                    ],
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
            'csrf_field_name' => 'token',
            'csrf_token_id' => 'authenticate',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ];
    }
}
