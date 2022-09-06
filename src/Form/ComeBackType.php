<?php

namespace App\Form;

use App\Entity\ComeBack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComeBackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', null, ['label' => 'Nom', 'help'=>'Le champ doit contenir entre 4 et 50 caractères'])
            ->add('firstName', null, ['label' => 'Prénom', 'help'=>'Le champ doit contenir entre 4 et 50 caractères'])
            ->add('Address', null, ['label' => 'Adresse', 'help'=>'Le champ doit contenir entre 4 et 255 caractères'])
            ->add('mobile', null, ['label' => 'Téléphone', 'help'=>'Le champ doit contenir entre 9 et 10 chiffres'])
            ->add('birthDate', DateType::class, array(
                'label' => 'Date de naissance',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y') - 100),
                'months' => range(1, 12),
                'days' => range(1, 31)
            ))
            ->add('nationalNumber', null, ['label' => 'Numéro National',  'attr'=>['placeholder'=>'XX.XX.XX-XXX.XX'], 'help'=>'XX.XX.XX-XXX.XX'])
            ->add('submit', SubmitType::class, ['label' => 'Envoyé']);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ComeBack::class,
        ]);
    }
}
