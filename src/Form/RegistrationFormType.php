<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez le prénom de l\'utilisateur'
                    ])
                ]
            ])
            ->add('lastName', null, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez le nom famille de l\'utilisateur'
                    ])
                ]
            ])
            ->add('higherRole', ChoiceType::class, [
                'choices' => [
                    'Enseignant' => 0,
                    'Responsable de dîplome' => 1,
                    'Gestionnaire EDT' => 2,
                    'Administrateur' => 3
                ],
                'mapped' => false,
                'label' => 'Role de l\'utilisateur'
            ])

            // BUG HERE: constraint not working -> only client side validation active
            ->add('username', null, [
                'label' => 'Nom d\'utilisateur',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un nom d\'utilisateur'
                    ])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne coincident pas',
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe'
                ],
                'second_options' => [
                    'label' => 'Vérifiez le mot de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
