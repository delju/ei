<?php

namespace App\Form;

use App\Entity\ComeBack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComeBackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', null, ['label'=>'Nom'])
            ->add('firstName',null, ['label'=>'Prénom'])
            ->add('Address',null, ['label'=>'Adresse'])
            ->add('mobile',null, ['label'=>'Téléphone'])
            ->add('birthDate',DateType::class, array(
                'label' => 'Date de naissance',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y') - 100),
                'months' => range(1, 12),
                'days' => range(1, 31)
            ))
            ->add('nationalNumber', null,['label'=>'Numéro National']);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ComeBack::class,
        ]);
    }
}
