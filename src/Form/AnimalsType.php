<?php

namespace App\Form;

use App\Entity\Animals;
use App\Entity\GetOn;
use App\Entity\Races;
use App\Entity\Species;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label'=>'Nom'])
            ->add('species', EntityType::class, ['label'=>'Espèces' ,'class'=> Species::class])
            ->add('sexe', ChoiceType::class, ['choices' => ['Mâle' => false, 'Femelle'=> true], 'expanded'=>true])
            ->add('borthDate', null, ['label'=>'Date de naissance'])
            ->add('coat', null, ['label'=>'Robes'])
            ->add('personality', null, ['label'=>'Caractère'])
            ->add('getOn', EntityType::class, ['label'=>'S\'entend avec','class' => GetOn::class, "multiple"=>true, "expanded"=>true])
            ->add('health', null, ['label'=>'Santée'])
            ->add('arrivalReason', null, [])
            ->add('castrate', ChoiceType::class, ['label'=>'Castré','choices' => ['Oui'=> true, 'Non'=>false], 'expanded'=>true])
            ->add('identification', ChoiceType::class, ['label'=>'Identifié', 'choices'=>['Oui'=>true, 'Non'=>false], 'expanded'=>true] )
            ->add('identificationNumber', null,['label'=>'Numéro d\'identification'])
            ->add('lastChance', ChoiceType::class, ['label'=>'Dernière chance','choices' => ['Oui'=> true, 'Non'=>false], 'expanded'=>true])
            ->add('gallery', GalleryType::class)
            ->add('submit', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animals::class,
        ]);
    }
}
