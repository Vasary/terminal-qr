<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\User\Form;

use App\Application\Store\Business\StoreFacadeInterface;
use App\Domain\Model\Store;
use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\ChoiceType;
use App\Infrastructure\Form\SubmitType;
use App\Infrastructure\Form\TextType;

final class CreateType extends AbstractType
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
                    'label' => 'users.attribute.email',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'users.attribute.email',
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
                    'label' => 'users.attribute.password',
                    'trim' => true,
                    'attr' => [
                        'placeholder' => 'users.attribute.password',
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
                    'label' => 'users.attribute.roles',
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
                    'label' => 'users.attribute.assigned_stores',
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
                    'label' => 'users.button.create',
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
