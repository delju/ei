<?php


namespace App\Search;

use App\Entity\GetOn;
use App\Entity\Species;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFullType extends SearchType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', EntityType::class, ['class'=>Species::class, 'required'=> false])
            ->add('sexe', ChoiceType::class, ['choices' => ['Mâle' => false, 'Femelle' => true],'multiple'=>false, 'expanded'=>true ,'required'=>false])
            ->add('getOns', EntityType::class, ['class'=>GetOn::class, 'required'=> false, 'multiple' => true, 'expanded' => true])
            ->add('lastChance', CheckboxType::class, ['required'=>false])
            ->add('submit', SubmitType::class, ['label'=>'Recherche'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection'=> false,
            'data_class' => Search::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

}
