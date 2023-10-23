<?php

namespace App\Form;

use App\Entity\Vacance;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class VacanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jourDeDebut', DateType::class, [
                'widget' => "single_text",
                'input' => 'datetime_immutable'
            ])
            ->add('jourDeFin', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vacance::class,
        ]);
    }
}
