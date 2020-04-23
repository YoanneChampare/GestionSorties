<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,['label'=>'Nom du Lieu'])
            ->add('rue',TextType::class,['label'=>'Nom et Numéro de la Rue'])
            ->add('latitude',IntegerType::class,['label'=>' Renseigner la Latitude'])
            ->add('longitude',IntegerType::class,['label'=>'Renseigner la Longitude'])
            ->add('ville', EntityType::class, [
               'class' => Ville::class,
               'query_builder' => function (EntityRepository $er) {
                   return $er->createQueryBuilder('v')
                       ->orderBy('v.nom', 'ASC');
               },
               'choice_label' => 'nom'
           ])


        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
            'method'=> 'GET',
        ]);
    }
}
