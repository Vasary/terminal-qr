<?php

declare(strict_types = 1);

namespace App\Infrastructure\Form;

use App\Presentation\UI\Management\Module\Gateway\Form\Credential;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CredentialType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'key',
                TextType::class,
                [
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Document description, eg: Ticket, receipt, itinerary, map, etc…',
                    ],
                ]
            )
            ->add(
                'value',
                TextType::class,
                [
                    'mapped' => false,
                    'required' => true,
                ]
            );

        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Credential::class,
            ]
        );
    }
}
