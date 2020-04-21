<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
           /* ->add('etat',TextType::class,['label'=>'Etat'])*/

      //      ->add('lieu', 'entity', array(
       //         'class' => 'App\Entity\Lieu',
        //        'property' => 'label',
        //        'multiple' => true,
        //        'expanded' => false
        //    ))
           ->add('lieu', EntityType::class, [
               'class' => Lieu::class,
               'query_builder' => function (EntityRepository $er) {
                   return $er->createQueryBuilder('l')
                       ->orderBy('l.nom', 'ASC');
               },
               'choice_label' => 'nom'
           ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'method'=> 'GET',
        ]);
    }
}
