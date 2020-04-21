<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\LoginType;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ParticipantController extends Controller
{



    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils){

        $participant=new Participant();
        $participantForm=$this->createForm(ParticipantType::class,$participant);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $mail = $authenticationUtils->getLastUsername();


        return $this->render('participant/login.html.twig', [
            "formulaire"=>$participantForm->createView(),
            'page_name'=>'Se connecter',
            'mail' => $mail,
            'error'=> $error,
        ]);
    }

    /**
     * @Route("/logout",name="logout")
     */
    public function logout(){

    }

    /**
     * @Route("/profil", name="profil")
     */

    public function afficherProfil(Request $request,EntityManagerInterface $em,UserPasswordEncoderInterface $encode){
        $this->denyAccessUnlessGranted("ROLE_USER");
       $user=$this->getUser();
        $participantForm = $this->createForm(ParticipantType::class,$user);

        $participantForm->handleRequest($request);
        if($participantForm->isSubmitted() && $participantForm->isValid()){
           $data=$participantForm->getData();
            $hash=$encode->encodePassword($user,$data->getMdp());
            $user->setMdp($hash);


           /* $file = $participantForm['avatar']->getData();
            if($file){
                $fileName=pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

                $newFileName=$fileName.'-'.uniqid().'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('file_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setAvatar($newFileName);
            }*/
           $em->persist($data);
           $em->flush();

        }
        return $this->render("participant/profil.html.twig",[
            "participant"=>$user,
            'page_name'=>'Mon profil',
            "formulaire"=>$participantForm->createView(),
        ]);
    }

 //   /**
  //   * @Route("/profilParticipant{id}",name="profilParticipant",requirements={"id":"\d+"})
//    * @param EntityManagerInterface $em
 //    * @param Request $request
 //    * @param Participant $profilParticipant
 //    * @return Response
 //    */
//    public function profilParticipant(EntityManagerInterface $em,Request $request, Participant $profilParticipant){

//        $profilParticipantForm = $this->createForm(ParticipantType::class,$profilParticipant);
//
//        $profilParticipantForm ->handleRequest($request);

 //       if( $profilParticipantForm ->isSubmitted()){
 //           $em->persist($profilParticipant);
 //           $em->flush();
  //      }

  //      return $this->render('participant/profilParticipant.html.twig',[
  //          "profilParticipant"=>profilParticipant,
  //          "page_name"=>"profilParticipant",
  //          "profilParticipantForm"=> $profilParticipantForm ->createView()
  //      ]);
  //  }



}
