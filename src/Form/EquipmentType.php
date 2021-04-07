<?php

namespace App\Form;

use App\Entity\Equipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EquipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'constraints' => [
                    new NotNull([
                        'message' => 'Il faut indiquer le nom du matériel à ajouter'
                    ])
                    ],
                    'label' => 'Nom du matériel'
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Logiciel' => 'Logiciel',
                    'Matériel Physique' => 'Matériel Physique',
                    'Système d\'exploitation' => 'Système d\'exploitation'
                ],
                'label' => 'Catégorie'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
        ]);
    }
}
