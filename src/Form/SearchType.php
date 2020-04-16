<?php

namespace App\Form;

use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motCle', TextType::class,
                ['label' => 'Le nom de la sortie contient',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Search'
                    ]
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
