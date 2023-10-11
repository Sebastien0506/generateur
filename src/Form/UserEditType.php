<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Contrat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'email',
                'attr' => [
                    'class' => "form-control",
                ],
                'constraints' => [
                    new NotBlank([
                        "message" => "Veuillez saisir une adresse email",
                    ])
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'nom',
                'attr' => [
                    'class' => "form-control",
                ],
                'constraints' => [
                    new NotBlank([
                        "message" => "Veuillez saisir un nom",
                    ])
                ]
            ] )
            ->add('prenom', TextType::class, [
                'label' => 'prenom',
                'attr' => [
                    'class' => "form-control",
                ],
                'constraints' => [
                    new NotBlank([
                        "message" => "Veuillez saisir un prenom",
                    ])
                ]
            ])
            ->add('badge', ChoiceType::class, [
                'label' => 'Badge',
                'choices' => [ // On demande si l'employer qui est ajouté est badger
                    "Oui" => "yes",
                    "Non" => "no",
                ],
                "expanded" => true,
            ])
            ->add('age', ChoiceType::class, [
                'label' => 'Majeur ?',
                'choices' => [ // On demande si l'employer qui est ajouté est majeur
                    "Oui" => "yes",
                    "Non" => "no",
                ],
                "expanded" => true,
            ])
            ->add('contrat', EntityType::class, [
                'class' => Contrat::class,
                'choice_label' => 'type_contrat',
                "expanded" => true,
                "constraints" => [
                   new NotBlank([
                    "message" => "Veuillez selectionner un contrat."
                   ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
