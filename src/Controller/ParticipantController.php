<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\LoginType;
use App\Form\ParticipantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ParticipantController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('participant/index.html.twig', [
            'page_name'=>'accueil',
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils){

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $mail = $authenticationUtils->getLastUsername();


        return $this->render('participant/login.html.twig', [

            'page_name'=>'Se connecter',
            'mail' => $mail,
            'error'         => $error,
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
    public function afficherProfil(){
        $idParticipant=$this->getUser()->getId();
        $participantRepo=$this->getDoctrine()->getRepository(Participant::class);
        $participant=$participantRepo->find($idParticipant);

        $participantForm = $this->createForm(ParticipantType::class,$participant);
        return $this->render("participant/profil.html.twig",[
            "participant"=>$participant,
            'page_name'=>'Mon profil',
            "formulaire"=>$participantForm->createView(),
        ]);
    }

    /**
     * @Route("/profil/modifier", name="modifier_profil")
     */

    public function modifierProfil(Request $request){
        $idParticipant=$this->getUser()->getId();
        $profil= new Participant();
        $participantForm = $this->createForm(ParticipantType::class,$profil);
        $participantForm->handleRequest($request);

        return $this->render("participant/modifier_profil.html.twig",[
            "formulaire"=>$participantForm->createView(),
            'page_name'=>'Modifier profil'
        ]);
    }

}
