<?php

namespace App\Form;

use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la matière',
                'attr' => [
                    'placeholder' => 'Développement Web'
                ]
            ])
            ->add('PPN', null, [
                'label' => 'PPN ou abbréviation (Pas d\'underscore ni de numéro de semestre, cela se fait tout seul)',
                'attr' => [
                    'placeholder' => 'DEV'
                ],
                
            ])
            ->add('CA', null, [
                'label' => 'Code apogée (facultatif)'
            ])
            ->add('semester', null, [
                'label' => 'N° de semestre'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subject::class,
        ]);
    }
}
