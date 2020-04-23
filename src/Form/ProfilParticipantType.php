<?php


namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                  ->add('pseudo',TextType::class,[
                      "label"=>"Nom",
                      'disabled'=>true,
                  ])
                  ->add('nom',TextType::class,[
                      'label'=>'Nom ',
                      'disabled'=>true,
                      ])
                  ->add('prenom',TextType::class,[
                      'label'=>'Prénom',
                      'disabled'=>true,
                      ])
                  ->add('telephone',TextType::class,[
                      'label'=>'Téléphone',
                      'disabled'=>true,
                      ])
                  ->add('mail',EmailType::class,[
                      'label'=>'E-mail',
                      'disabled'=>true,
                      ])
                  ->add('site',EntityType::class,[
                      "label"=>"Site de rattachement",
                      'disabled'=>true,
                      "class"=>Site::class,
                      "choice_label"=>function($site){
                          return $site->getNom();
                      }
                  ])
              ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }

}