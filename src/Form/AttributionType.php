<?php

namespace App\Form;

use App\Entity\Attribution;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cmAmount', null, [
                'label' => 'Cours magistraux'
            ])
            ->add('tdAmount', null, [
                'label' => 'Cours de TD'
            ])
            ->add('tpAmount', null, [
                'label' => 'Cours de TP'
            ])
            ->add('subject', EntityType::class, [
                'label' => 'Matière',
                'class' => 'App\Entity\Subject'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attribution::class,
        ]);
    }
}
