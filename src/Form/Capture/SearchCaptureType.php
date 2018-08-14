<?php
// src/Form/Capture/SearchCaptureType.php

namespace App\Form\Capture;

use App\Entity\Bird;
use App\Repository\BirdRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCaptureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bird',
                EntityType::class, array(
                    'class' => Bird::class,
                    'query_builder' => function (BirdRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.vernacularname', 'ASC');
                        },
                    'choice_label' => 'vernacularname',
                    'label' => ' ',
                    'placeholder' => 'Oiseau',
                    )
            )
            ->add('region',
                ChoiceType::class,
                array(
                    'choices'  => array(
                        'Alsace-Champagne-Ardenne-Lorraine' => 'Alsace-Champagne-Ardenne-Lorraine',
                        'Aquitaine-Limousin-Poitou-Charentes' => 'Aquitaine-Limousin-Poitou-Charentes',
                        'Auvergne-Rhône-Alpes' => 'Auvergne-Rhône-Alpes',
                        'Bourgogne-Franche-Comté' => 'Bourgogne-Franche-Comté',
                        'Bretagne' => 'Bretagne',
                        'Centre-Val de Loire' => 'Centre-Val de Loire',
                        'Corse' => 'Corse',
                        'Ile-de-France' => 'Ile-de-France',
                        'Languedoc-Roussillon-Midi-Pyrénées' => 'Languedoc-Roussillon-Midi-Pyrénées',
                        'Nord-Pas-de-Calais-Picardie' => 'Nord-Pas-de-Calais-Picardie',
                        'Normandie' => 'Normandie',
                        'Pays de la Loire' => 'Pays de la Loire',
                        'Provence-Alpes-Côte d\'Azur' => 'Provence-Alpes-Côte d\'Azur',
                        ),
                    'label' => ' ',
                    'placeholder' => 'Région',
                    )
            )
            ->add('Rechercher',
                SubmitType::class
            )
        ;
    }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
    	'data_class' => null,
	  ));
  }
}
