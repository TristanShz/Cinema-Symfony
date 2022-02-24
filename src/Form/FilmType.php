<?php

namespace App\Form;

use App\Entity\Film;
use DateTime;
use Doctrine\DBAL\Types\DateType as TypesDateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceLabel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('genre')
            ->add('realisateur')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ]) 
            ->add('duree', NumberType::class)
            ->add('synopsis', TextareaType::class)
            ->add('status', ChoiceType::class,[
                'choices' => [
                    'En cours de diffusion' => 'En cours de diffusion',
                    'Plus diffusé' => 'Plus diffusé',
                    'A venir' => 'A venir',
                ],
            ])
            ->add('image', FileType::class, [
                'mapped' => true,
                'required' => false,
                'constraints' => [
                    new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png'
                    ],
                    'mimeTypesMessage' => 'Veuillez upload une image de type JPEG ou PNG'
                    ])
                ]   
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
