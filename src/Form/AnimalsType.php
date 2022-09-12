<?php

namespace App\Form;

use App\Entity\Animals;
use App\Entity\GetOn;
use App\Entity\Species;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nom'])
            ->add('species', EntityType::class, ['label' => 'Espèces', 'class' => Species::class])
            ->add('race', null, ['label' => 'Race'])
            ->add('sexe', ChoiceType::class, ['choices' => ['Mâle' => false, 'Femelle' => true], 'expanded' => true])
            ->add('birthDate', DateType::class, array(
                'label' => 'Date de naissance',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y') - 30),
                'months' => range(1, 12),
                'days' => range(1, 31)
            ))
            ->add('coat', null, ['label' => 'Robes'])
            ->add('personality', null, ['label' => 'Caractère'])
            ->add('getOn', EntityType::class, ['label' => 'S\'entend avec', 'class' => GetOn::class, "multiple" => true, "expanded" => true])
            ->add('health', null, ['label' => 'Santée'])
            ->add('arrivalReason', null, ['label'=>'Raison de l\'arrivée'])
            ->add('castrate', ChoiceType::class, ['label' => 'Castré', 'choices' => ['Oui' => true, 'Non' => false], 'expanded' => true])
            ->add('identification', ChoiceType::class, ['label' => 'Identifié', 'choices' => ['Oui' => true, 'Non' => false], 'expanded' => true])
            ->add('identificationNumber', null, ['label' => 'Numéro d\'identification'])
            ->add('lastChance', ChoiceType::class, ['label' => 'Dernière chance', 'choices' => ['Oui' => true, 'Non' => false], 'expanded' => true])
            ->add('gallery', GalleryType::class)
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animals::class,
        ]);
    }
}
