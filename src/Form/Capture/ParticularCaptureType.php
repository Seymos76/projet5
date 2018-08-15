<?php
// src/Form/Capture/ParticularCaptureType.php

namespace App\Form\Capture;

use App\Entity\Bird;
use App\Repository\BirdRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticularCaptureType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder 
      ->add('bird',            EntityType::class, array(
        'class' => Bird::class,
        'query_builder' => function (BirdRepository $er) {
          return $er->createQueryBuilder('u')
            ->orderBy('u.vernacularname', 'ASC');
        },
        'choice_label' => 'vernacularname',
        'label' => ' ',
        'placeholder' => 'Sélectionner un oiseau',
      ))
      ->add('image',            FileType::class, array(
        'label' => 'Ajouter une image :',
        'required'   => false,
      ))
      ->add('content',            TextareaType::class, array(
        'label' => 'Observation :',
      ))
      ->add('latitude',   NumberType::class, array(
        'label' => 'Latitude'
      ))
      ->add('longitude',      NumberType::class, array(
        'label' =>  'Longitude',
      ))
      ->add('address', TextType::class, array(
        'label' => ' ',
        'attr' => array(
        'placeholder' => 'Adresse',
        )
      ))
      ->add('complement', TextType::class, array(
        'label' => ' ',
        'required'   => false,
        'attr' => array(
          'placeholder' => 'Complément d\'adresse',
        )
      ))
      ->add('zipcode', NumberType::class, array(
        'label' => ' ',
        'attr' => array(
          'placeholder' => 'Code postal',
        )
      ))
      ->add('city',        TextType::class, array(
        'label' => ' ',
        'attr' => array(
          'placeholder' => 'Ville',
        )
      ))
      ->add('region', TextType::class, array(
        'label' => ' ',
        'attr' => array(
        'placeholder' => 'Région',
        )
      ))
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
    	'data_class' => 'App\Entity\Capture',
	  ));
  }
}