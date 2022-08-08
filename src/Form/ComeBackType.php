<?php

namespace App\Form;

use App\Entity\ComeBack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComeBackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName')
            ->add('firstName')
            ->add('Address')
            ->add('mobile')
            ->add('borthDate')
            ->add('nationalNumber')
            ->add('date')
            ->add('animals')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ComeBack::class,
        ]);
    }
}
