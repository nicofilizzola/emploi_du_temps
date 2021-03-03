<?php

namespace App\Form;

use App\Entity\Session;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', null, [
                'label' => 'À partir de :',
                'data' => new DateTimeImmutable,
                //'class' => 'form-control'
            ])
            ->add('until', null, [
                // add one year
                'label' => 'Jusqu\'à :',
                'data' => new DateTimeImmutable
            ])
        ;
        // CSRF VALIDATION PENDING
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
