<?php

declare(strict_types = 1);

namespace App\Infrastructure\Form;

use App\Presentation\UI\Management\Module\Payment\Form\Log;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class LogType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'createdAt',
                TextType::class,
                [
                    'disabled' => true,
                ]
            )
            ->add(
                'text',
                TextType::class,
                [
                    'disabled' => true,
                ]
            );

        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Log::class,
            ],
        );
    }
}
