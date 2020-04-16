<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,['label'=>'Nom de la sortie'])
            ->add('dateHeureDebut',DateTimeType::class,['label'=>'Date de début de la Sortie'])
            ->add('dateLimiteInscription',DateTimeType::class,['label'=>'Date Limite d\'inscription'])
            ->add('nbInscriptionsMax',IntegerType::class,['label'=>'Nombres de places'])
            ->add('duree',TimeType::class,['label'=>'Durée de la Sortie'])
            ->add('infosSortie',TextareaType::class,['label'=>'Description et infos'])

            ->add('lieu',TextType::class,['label'=>'Lieu'])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
