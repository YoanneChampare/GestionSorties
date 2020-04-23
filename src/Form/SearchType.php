<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nSite',EntityType::class,[
                'required'=>false,
                'label'=>'Site :',
                'class'=>Site::class,
                'choice_label'=>function($site){
                return $site->getNom();
                }
    ])
            ->add('motCle', TextType::class,
                ['label' => 'Le nom de la sortie contient',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Search'
                    ]
                ])

            ->add('datemin',DateTimeType::class,[
                "label"=>'Entre',
                'required'=>false
            ])

            ->add('datemax',DateTimeType::class,[
                "label"=>'et',
                'required'=>false
            ])

            ->add('sOrganisateur',CheckboxType::class,[
                'label'=>'Sorties dont je suis l\'organisateur/trice',
                'required'=>false
            ])
            ->add('sInscrit',CheckboxType::class,[
                'label'=>'Sorties auxquelles je suis inscrit/e',
                'required'=>false
            ])
            ->add('sNonInscrit',CheckboxType::class,[
                'label'=>'Sorties auxquelles je ne suis pas inscrit/e',
                'required'=>false
            ])
            ->add('sPasse',CheckboxType::class,[
                'label'=>'Sorties passées',
                'required'=>false
            ])

         ;



}


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method'=> 'GET',
            'csrf_protection'=>false
        ]);
    }

    public function  getBlockPrefix()
    {
        return '';
    }
}
