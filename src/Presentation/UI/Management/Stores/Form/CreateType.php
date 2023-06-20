<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Stores\Form;

use App\Application\Gateway\Business\GatewayFacadeInterface;
use App\Domain\Model\Gateway;
use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\ChoiceType;
use App\Infrastructure\Form\SubmitType;
use App\Infrastructure\Form\TextAreaType;
use App\Infrastructure\Form\TextType;

final class CreateType extends AbstractType
{
    public function __construct(private readonly GatewayFacadeInterface $gatewayFacade) {}

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
                'type' => 'code',
                'class' => TextType::class,
                'options' => [
                    'required' => true,
                    'label' => 'Code',
                    'attr' => [
                        'placeholder' => 'Code',
                    ],
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ],
            ],
            [
                'type' => 'gateways',
                'class' => ChoiceType::class,
                'options' => [
                    'required' => true,
                    'label' => 'Attached gateway',
                    'multiple' => true,
                    'expanded' => true,
                    'row_attr' => [
                        'class' => '',
                    ],
                    'choices' => $this->createChoiceList(),
                ],
            ],
            [
                'type' => 'description',
                'class' => TextAreaType::class,
                'options' => [
                    'required' => false,
                    'help' => 'Optional description for a new store',
                    'label' => ' ',
                    'attr' => [
                        'placeholder' => '',
                        'rows' => 4,
                        'cols' => 50,
                    ],
                    'row_attr' => [
                        'class' => 'mb-3',
                    ],
                ],
            ],
            [
                'type' => 'submit',
                'class' => SubmitType::class,
                'options' => [
                    'label' => 'Create',
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
            'csrf_field_name' => 'store_create',
            'csrf_token_id' => 'store_create',
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ];
    }

    private function createChoiceList(): array
    {
        $list = [];
        foreach ($this->gatewayFacade->find() as $gateway) {
            /** @var Gateway $gateway */
            $list[(string) $gateway->title()] = (string) $gateway->id();
        }

        return $list;
    }
}
