<?php

namespace App\Form;

use App\Entity\Adoption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdoptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', null, ['label'=>'Nom'])
            ->add('firstName', null, ['label'=>'Prénom'])
            ->add('Address', null, ['label'=>'Adresse'])
            ->add('mobile', null, ['label'=>'Numéro de téléphone'])
            ->add('birthDate', DateType::class, array(
                'label' => 'Date de naissance',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y') - 100),
                'months' => range(1, 12),
                'days' => range(1, 31)
            ))
            ->add('nationalNumber', null, ['label'=>'Numéro National'])
            ->add('gallery', GalleryType::class, ['label'=>'Documents'])
            ->add('submit', SubmitType::class, ['label'=>'Envoyé']);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adoption::class,
        ]);
    }
}
