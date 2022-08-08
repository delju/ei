<?php

namespace App\Form;

use App\Entity\Animals;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('Sexe')
            ->add('borthDate')
            ->add('castrate')
            ->add('coat')
            ->add('personality')
            ->add('lastChance')
            ->add('health')
            ->add('dateArrival')
            ->add('slug')
            ->add('species')
            ->add('getOn')
            ->add('arrivalReason')
            ->add('death')
            ->add('Adoption')
            ->add('comeBack')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animals::class,
        ]);
    }
}