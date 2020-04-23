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
        $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
        $participantliste = $participantRepo->findAll();
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->findAll();

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
            'page_name' => 'Gestion administateur ',
            "participants" => $participantliste,
            "sorties" => $sortie,
            "formulaire" => $participantForm->createView()

        ]);
    }

    /**
     * @Route("/deleteParticipant/{id}",name="deleteParticipant",requirements={"id":"\d+"})
     */
    public function deleteParticipant($id,EntityManagerInterface $em){

        $participantRepo= $this->getDoctrine()->getRepository(Participant::class);
        $participant= $participantRepo->Delete_Participant($id);

$this->addFlash("success","Utilisateur supprimé avec succès");
        return $this->redirectToRoute("gestion_admin");

    }


}
