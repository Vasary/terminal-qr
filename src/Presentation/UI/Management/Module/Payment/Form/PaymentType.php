<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Payment\Form;

use App\Infrastructure\Form\AbstractType;
use App\Infrastructure\Form\CheckboxType;
use App\Infrastructure\Form\CollectionType;
use App\Infrastructure\Form\LogType;
use App\Infrastructure\Form\TextType;

final class PaymentType extends AbstractType
{
    public function declareTypes(): array
    {
        return [
            [
                'type' => 'id',
                'class' => TextType::class,
                'options' => [
                    'disabled' => true,
                    'label' => 'payment.form.id.value',
                ],
            ],
            [
                'type' => 'amount',
                'class' => TextType::class,
                'options' => [
                    'disabled' => true,
                    'label' => 'payment.form.amount.value',
                    'attr' => [
                        'placeholder' => 'payment.form.field.empty',
                    ],
                ],
            ],
            [
                'type' => 'commission',
                'class' => TextType::class,
                'options' => [
                    'disabled' => true,
                    'label' => 'payment.form.commission.value',
                    'attr' => [
                        'placeholder' => 'payment.form.field.empty',
                    ],
                ],
            ],
            [
                'type' => 'qrExists',
                'class' => CheckboxType::class,
                'options' => [

                    'disabled' => true,
                    'label' => 'payment.form.qr_exists.value',
                ],
            ],
            [
                'type' => 'qr',
                'class' => TextType::class,
                'options' => [
                    'disabled' => true,
                    'label' => 'payment.form.qr_image.value',
                ],
            ],
            [
                'type' => 'store',
                'class' => TextType::class,
                'options' => [
                    'disabled' => true,
                    'label' => 'payment.form.store.value',
                    'attr' => [
                        'placeholder' => 'payment.form.field.empty',
                    ],
                ],
            ],
            [
                'type' => 'gateway',
                'class' => TextType::class,
                'options' => [
                    'disabled' => true,
                    'label' => 'payment.form.gateway.value',
                    'attr' => [
                        'placeholder' => 'payment.form.field.empty',
                    ],
                ],
            ],
            [
                'type' => 'logs',
                'class' => CollectionType::class,
                'options' => [
                    'entry_type' => LogType::class,
                    'by_reference' => false,
                    'entry_options' => [
                        'label' => false,
                    ],
                    'block_prefix' => 'logs',
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
            'data_class' => PaymentData::class,
            'block_prefix' => 'detail',
        ];
    }
}
