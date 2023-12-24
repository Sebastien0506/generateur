<?php

namespace App\Form;

use App\Entity\Boutique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class BoutiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_boutique', TextType::class, [
                'label' => 'Nom de la boutique',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        "message" => "Veuillez saisir le nom de la boutique",
                    ])
                ]
            ])
            ->add('horaireDebut', TimeType::class, [
                'label' => "Horaire d'ouverture de la boutique",
                
                'constraints' => [
                    new NotBlank([
                        "message" => "Veuillez saisir les horaire d'ouverture de la boutique",
                    ])
                ]
            ]) 
            ->add('horaireFin', TimeType::class, [
                'label' => 'Horaire de fermeture',
                'constraints' => [
                    new NotBlank([
                        "message" => "Veuillez saisir les horaire de fermeture de la boutique",
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boutique::class,
        ]);
    }
}
