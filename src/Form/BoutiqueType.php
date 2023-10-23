<?php

namespace App\Form;

use App\Entity\Boutique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            ->add('horaire_ouverture', TextType::class, [
                'label' => "Horaire d'ouverture de la boutique",
                'attr' => [
                    "class" => "form-control"
                ],
                'constraints' => [
                    new NotBlank([
                        "message" => "Veuillez saisir les horaire d'ouverture de la boutique",
                    ])
                ]
            ]) 
            ->add('horaire_fermeture', TextType::class, [
                'label' => 'Horaire de fermeture',
                'attr' => [
                    "class" => "form-control"
                ],
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
