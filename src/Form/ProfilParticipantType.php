<?php


namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                      "class"=>Participant::class,
                      "choice"=>function($participant){
                          return $participant->getPseudo();
                      }
                  ])
                  ->add('nom',TextType::class,[
                      'label'=>'Nom ',
                      'disabled'=>true,
                      "class"=>Participant::class,
                      "choice"=>function($participant){
                          return $participant->getNom();
                          }
                      ])
                  ->add('prenom',TextType::class,[
                      'label'=>'Prénom',
                      'disabled'=>true,
                      "class"=>Participant::class,
                      "choice"=>function($participant){
                          return $participant->getPrenom();
                          }
                      ])
                  ->add('telephone',TextType::class,[
                      'label'=>'Téléphone',
                      'disabled'=>true,
                      "class"=>Participant::class,
                      "choice"=>function($participant){
                          return $participant->getTelephone();
                          }
                      ])
                  ->add('mail',EmailType::class,[
                      'label'=>'E-mail',
                      'disabled'=>true,
                      "class"=>Participant::class,
                      "choice"=>function($participant){
                          return $participant->getEmail();
                          }
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
            'data_class' => Sortie::class,
        ]);
    }

}