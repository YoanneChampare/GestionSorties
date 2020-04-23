<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo',TextType::class)
            ->add('nom',TextType::class)
            ->add('prenom',TextType::class)
            ->add('telephone',TextType::class)
            ->add('mail',EmailType::class)
            ->add('mdp',RepeatedType::class,[
                "type"=>PasswordType::class,
                "invalid_message"=>"The password field must match.",
                "options"=>["attr"=>["class"=>"password-field"]],
                "required"=>true,
                "first_options"=>["label"=>"Password"],
                "second_options"=>["label"=>"Repeat Password"]
            ])
            ->add('site',EntityType::class,[
                "label"=>"Site de rattachement",
                "class"=>Site::class,
                "choice_label"=>function($site){
                return $site->getNom();
                }
            ])
            /*->add('avatar',FileType::class,[
                "label"=>"Ma photo"
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
