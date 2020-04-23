<?php

namespace App\Form;

use App\Data\AfficherData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AfficherType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motCle', TextType::class,
                ['label' => 'Le nom contient',
                    'required' => false,
                    'attr' => [
                            'placeholder' => 'Rechercher'
                    ]
                ])
         ;



}


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AfficherData::class,
            'method'=> 'GET',
            'csrf_protection'=>false
        ]);
    }

    public function  getBlockPrefix()
    {
        return '';
    }
}
