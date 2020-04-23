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
     * @Route("/", name="acc")
     */
    public function accueil(){
        return $this->redirectToRoute("accueil");
    }

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

    /**
     * @Route("/addParticipant", name="addParticipant")
     */
    public function addParticipant(Request $request,EntityManagerInterface $em,UserPasswordEncoderInterface $encode){
        $participant = new Participant();
        $participantForm= $this->createForm(ParticipantType::class,$participant);

        $participantForm->handleRequest($request);
        if($participantForm->isSubmitted() && $participantForm->isValid()){

            $hash=$encode->encodePassword($participant,$participant->getMdp());
            $participant->setMdp($hash);

            $em->persist($participant);
            $em->flush();

            $this->addFlash("success","Enregistrement OK!");

        }

        return $this->render("participant/inscriptionParticipant.html.twig",[
            'page_name'=>'Inscription',
            "formulaire"=>$participantForm->createView()]);
    }


    /**
     * @Route("/resetPassword",name="reset_password")
     */
    public  function resetPassord(Request $request,UserPasswordEncoderInterface $encode,EntityManagerInterface $em){

        $repo=$this->getDoctrine()->getRepository(Participant::class);
        $passwordForm=$this->createForm(ParticipantType::class);
        $checkMail=$repo->findAll();

        $passwordForm->handleRequest($request);
        $check=false;
        if($passwordForm->isSubmitted() && $passwordForm->isValid()){
            foreach($checkMail as $m){
                if(strcmp($m->getMail(),$request->getMail())){
                    $user=$m;
                    $check=true;
                    exit;
                }
            }

            if($check){
                $hash=$encode->encodePassword($m,$request->getMdp());
                $m->setMdp($hash);

                $em->persist($m);
                $em->flush();
            }
        }
        return  $this->render("participant/resetPassword.html.twig",[
            "page_name"=>"Réinitialisation de mot de passe",
            "formulaire"=>$passwordForm->createView()
        ]);


    }
}
