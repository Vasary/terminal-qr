<?php

declare(strict_types = 1);

namespace App\Infrastructure\Form;

use Symfony\Component\Form\AbstractType as Base;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType extends Base
{
    abstract function getOptions(): array;

    abstract function declareTypes(): array;

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults($this->getOptions());
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->declareTypes() as $type) {
            $builder->add($type['type'], $type['class'], $type['options']);
        }
    }
}
