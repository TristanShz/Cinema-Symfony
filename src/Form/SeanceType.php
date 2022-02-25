<?php

namespace App\Form;

use App\Entity\Film;
use App\Entity\Salles;
use App\Entity\Seance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateTimeType::class, [
                'widget' => 'single_text',   
                'label' => 'Date et Heure de début' 
            ])
            ->add('lang', ChoiceType::class, [
                'label' => 'Version',
                'choices' => [
                    'VO' => 'VO',
                    'VF' => 'VF'
                ]
            ])
            ->add('film', EntityType::class, ['class' => Film::class, 'choice_label' => 'title'])

            ->add('salles', EntityType::class, ['class' => Salles::class, 'choice_label' => 'numSalles'])
            ->add('save', SubmitType::class, ['label' => 'Ajouter la séance'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
