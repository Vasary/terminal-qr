<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Form;

use App\Domain\ValueObject\Credentials\SPB;
use App\Domain\ValueObject\Credentials\Stub;
use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\ChoiceType;
use App\Infrastructure\Form\CollectionType;
use App\Infrastructure\Form\CredentialType;
use App\Infrastructure\Form\TextType;

abstract class MutationType extends AbstractType
{
    public function declareTypes(): array
    {
        return [
            [
                'type' => 'title',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'gateways.attribute.title.value',
                    'trim' => true,
                    'help' => 'gateways.attribute.title.help',
                    'attr' => [
                        'placeholder' => '',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'type',
                'class' => ChoiceType::class,
                'options' => [
                    'required' => true,
                    'label' => 'gateways.attribute.type.value',
                    'trim' => true,
                    'help' => 'gateways.attribute.type.help',
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => [
                        'gateways.attribute.type.credentials.mock' => Stub::class,
                        'gateways.attribute.type.credentials.spb' => SPB::class,
                    ],
                ],
            ],
            [
                'type' => 'credentials',
                'class' => CollectionType::class,
                'options' => [
                    'entry_type' => CredentialType::class,
                    'by_reference' => false,
                    'entry_options' => [
                        'label' => false,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => true,
                    'block_prefix' => 'credentials',
                ],
            ],
            [
                'type' => 'callback',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'gateways.attribute.callback.value',
                    'trim' => true,
                    'help' => 'gateways.attribute.callback.help',
                    'attr' => [
                        'placeholder' => '',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
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
            'data_class' => Data::class,
        ];
    }
}
