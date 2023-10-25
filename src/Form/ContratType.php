<?php

namespace App\Form;

use App\Entity\Contrat;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_contrat', ChoiceType::class, [
                'label' => "Type de contrat:",
                'choices' => [
                   'CDI' => 'CDI',
                   'CDD' => 'CDD',
                   'Étudiant' => "etudiant",
                   'Saisonnier' => 'saisonnier',
                ],
                'expanded' => true,
                
            ])
            // ->add('jour_travail_max')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
