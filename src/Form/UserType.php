<?php

namespace App\Form;

use App\Entity\Boutique;
use App\Entity\User;
use App\Entity\Contrat;
use App\Form\ContratType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
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
                    ]),
                    ]
                ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    "Admin" => 'ROLE_ADMIN',
                    "User" => 'ROLE_USER',
                    
                ],
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                
            ])
            ->add('password', PasswordType::class, [
                'label' => 'password',
                'attr' => [
                    'class' => "form-control",
                ],
                'constraints' => [
                    
                    new NotBlank([
                        'message' => 'Veuillez saisir votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => " Votre mot de passe doit contenir au minimun {{ limit }} caractère.",
                        'max' => 50,
                    ]),
                ]
            ])
            // ->add('isVerified')
            ->add('nom', TextType::class, [
                'label' => 'nom',
                'attr' => [
                    'class' => "form-control",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuiller saisir le nom de l'utilisateur."
                    ]),
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'prenom',
                'attr' => [
                    'class' => "form-control",
                ],
                'constraints' => [
                    new NotBlank([
                        "message" => "Veuiller saisir le prenom de l'utilisateur."
                    ]),
                ]
            ])
            ->add('badge', ChoiceType::class, [
                'label' => 'Badge',
                'choices' => [ // On demande si l'employer qui est ajouté est badger
                    "Oui" => "yes",
                    "Non" => "no",
                ],
                "expanded" => true,
                "mapped" => false,
            ])
            ->add('age', ChoiceType::class, [
                'label' => 'Majeur ?',
                'choices' => [ // On demande si l'employer qui est ajouté est majeur
                    "Oui" => "yes",
                    "Non" => "no",
                ],
                "expanded" => true,
                "mapped" => false,
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
            ->add('horaireDebut', TimeType::class, [
                'label' => 'Horaire de début',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir une heure de début."
                    ])
                ]
            ])
            ->add('horaireFin', TimeType::class, [
                'label' => 'Horaire de fin',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir une heure de fin."
                    ])
                ]
            ])
            ->add('dateDebut', DateType::class)
            ->add('dateFin', DateType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();
        
                if(!$user){
                    return;
                }
                if ($user->getTypeContrat() === 'cdd') {
                    $form->add('debut', DateType::class);
                    $form->add('fin', DateType::class);
                }
                elseif($user->getTypeContrat() === 'cdi'){
                    $form->add('debut', DateType::class);
                }
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();

                if(!$user){
                    return;
                }
        
                if (isset($user['type_contrat'])) {
                    if ($user['type_contrat'] === 'cdd'){
                        $form->add('debut', DateType::class);
                        $form->add('fin', DateType::class);
                    }
                    elseif ($user['type_contrat'] === 'cdi'){
                        $form->add('debut', DateType::class);
                        $user['dateFin'] = null;
                    }
                }
               
            })
           
            
                ->add('jourTravail', ChoiceType::class, [
                    'label' => 'Jour de travail',
                    'choices' => [
                        'Lundi' => 'Lundi',
                        'Mardi' => 'Mardi',
                        'Mercredi' => 'Mercredi',
                        'Jeudi' => 'Jeudi',
                        'Vendredi' => 'Vendredi',
                        'Samedi' => 'Samedi',
                        'Dimanche' => 'Dimanche',

                    ],
                    'multiple' => true,
                    'expanded' => true,

                ])
                ->add('boutique', EntityType::class, [
                    'class' => Boutique::class,
                    'choice_label' => 'nom_boutique',
                    'multiple' => true,
                    'expanded' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez séléctionner la boutique'
                        ]),
                    ]
                   
                ])
            ->getForm();
           
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
