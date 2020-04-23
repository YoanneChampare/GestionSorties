<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/gestion", name="gestion_admin")
     */
    public function gestionAdmin(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encode)
    {

        $participant = new Participant();
        $participantForm = $this->createForm(ParticipantType::class, $participant);

        $participantForm->handleRequest($request);
        if ($participantForm->isSubmitted() && $participantForm->isValid()) {

            $hash = $encode->encodePassword($participant, $participant->getMdp());
            $participant->setMdp($hash);
            $participant->setActif(true);
            $participant->setAdministrateur(false);

            $em->persist($participant);
            $em->flush();

            $this->addFlash("success", "Enregistrement OK!");


        }
        return $this->render('admin/admin.html.twig', [
            'page_name' => 'Gestion Administratives ',
            "formulaire" => $participantForm->createView()

        ]);
    }

    /**
     * @Route("/gestionPatiipant", name="gestionParticipant")
     */
    public function afficherAllParticipant(){
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participantliste = $participantRepo->findAll();

        return $this->render('participant/gestionAdmin/gestionParticipant.html.twig', [
            'page_name' => 'Gestion Participants ',
            "participants" => $participantliste,


        ]);
    }

    /**
     * @Route("/gestionSortie", name="gestionSortie")
     */
    public function afficherAllSorties(){
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->findAll();

        return $this->render('participant/gestionAdmin/gestionSortie.html.twig', [
            'page_name' => 'Gestion Sorties ',
            "sorties" => $sortie,


        ]);
    }

    /**
     * @Route("/deleteParticipant/{id}",name="deleteParticipant",requirements={"id":"\d+"})
     */
    public function deleteParticipant($id,EntityManagerInterface $em){

        $participantRepo= $this->getDoctrine()->getRepository(Participant::class);
        $participant= $participantRepo->Delete_Participant($id);

$this->addFlash("success","Utilisateur supprimé avec succès");
        return $this->redirectToRoute("gestionParticipant");

    }

    /**
     * @Route("/modifierProfil/{id}", name="modifier_profil")
     */
    public function modifierProfil($id,Request $request,EntityManagerInterface $em,UserPasswordEncoderInterface $encode){

        $repo=$this->getDoctrine()->getRepository(Participant::class);
        $user=$repo->find($id);
        $participant=new Participant();
        $participantForm = $this->createForm(ParticipantType::class,$participant);

        $participantForm->handleRequest($request);

       if($participantForm->isSubmitted() && $participantForm->isValid()){
           $user->setPseudo($participant->getPseudo());
           $user->setNom($participant->getNom());
           $user->setPrenom($participant->getPrenom());
           $user->setMail($participant->getMail());
           $user->setPseudo($participant->getPseudo());
           $user->setPseudo($participant->getPseudo());
           $user->setTelephone($participant->getTelephone());
           $user->setSite($participant->getSite());
           $user->setActif($participant->getActif());
           $user->setAdministrateur($participant->getAdministrateur());
            $em->persist($user);
            $em->flush();

            $this->addFlash("success","Modifications effectuées");
            return $this->redirectToRoute("gestionParticipant");

        }
        return $this->render("participant/modifierProfil.html.twig",[
            "participant"=>$user,
            'page_name'=>'Modifier profil utilisateur',
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
            return $this->redirectToRoute("gestion_admin");

        }

        return $this->render("participant/gestionAdmin/inscriptionParticipant.html.twig",[
            'page_name'=>'Inscription participants',
            "formulaire"=>$participantForm->createView()]);
    }

}
