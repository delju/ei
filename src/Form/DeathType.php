<?php

namespace App\Form;

use App\Entity\Death;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeathType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateType::class, array(
        'label' => 'Date du décès',
        'widget' => 'choice',
        'years' => range(date('Y'), date('Y')),
        'months' => range(1, 12),
        'days' => range(1, 31)))
            ->add('cause')
            ->add('submit', SubmitType::class, ['label'=>'Envoyé']);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Death::class,
        ]);
    }
}
