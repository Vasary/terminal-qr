<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Form;

use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\Model\Store;
use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\ChoiceType;
use App\Infrastructure\Form\SubmitType;
use App\Infrastructure\Form\TextType;

final class UpdateType extends AbstractType
{
    public function __construct(private readonly StoreFacadeInterface $facade) {}

    public function declareTypes(): array
    {
        return [
            [
                'type' => 'email',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'Email',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'Email',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'password',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'Password',
                    'empty_data' => '',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'Password',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'roles',
                'class' => ChoiceType::class,
                'options' => [
                    'required' => true,
                    'label' => 'Assigned roles',
                    'multiple' => false,
                    'expanded' => false,
                    'row_attr' => [
                        'class' => '',
                    ],
                    'choices' => ['Admin' => 'ROLE_ADMIN', 'Manager' => 'ROLE_MANAGER'],
                ],
            ],
            [
                'type' => 'stores',
                'class' => ChoiceType::class,
                'options' => [
                    'required' => true,
                    'label' => 'Assigned stores',
                    'multiple' => true,
                    'expanded' => true,
                    'row_attr' => [
                        'class' => '',
                    ],
                    'choices' => $this->createChoiceList(),
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
            'csrf_field_name' => 'user_create',
            'csrf_token_id' => 'user_create',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
            'data_class' => Data::class,
        ];
    }

    private function createChoiceList(): array
    {
        $list = [];
        foreach ($this->facade->find() as $store) {
            /** @var Store $store */
            $list[(string) $store->title()] = (string) $store->id();
        }

        return $list;
    }
}
