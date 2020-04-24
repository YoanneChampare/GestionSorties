<?php

namespace App\Controller;

use App\Data\AfficherData;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AfficherType;
use App\Form\ParticipantType;
use App\Form\SiteType;
use App\Form\VilleType;
use App\Repository\SiteRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            "sorties"=>$sortie


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





    /************************************************GESTION VILLES************************************************/
    /**
     * @Route("/accueil_ville", name="accueil_ville")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function accueil_ville(EntityManagerInterface $em,Request $request)
    {
        $user=$this->getUser();
        $filtre=new AfficherData();
        $villeRepo=$this->getDoctrine()->getRepository(Ville::class);

        $filtreForm=$this->createForm(AfficherType::class,$filtre);
        $filtreForm->handleRequest($request);
        $villes=$villeRepo->findSearch($filtre);

        return $this->render('ville/afficher_ville.html.twig', [
            'page_name'=>'Gérer les Villes ',
            "form"=>$filtreForm->createView(),
            "villes"=>$villes,

        ]);
    }

    /**
     * @Route("/add_ville", name="add_ville")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function add_ville(EntityManagerInterface $em,Request $request)
    {
        $ville=new Ville();
        $villeForm=$this->createForm(VilleType::class,$ville);
        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()) {
            $em->persist($ville);
            $em->flush();
            $this->addFlash("success", " Ville enregistrée avec succès !");
        }
        return $this->render('ville/add_ville.html.twig', ['villeForm'=>$villeForm->createView(),"page_name"=>"Ajouter Ville"]);
    }

    /**
     * @Route("/list_ville", name="list_ville")
     * @param VilleRepository $repository
     * @return Response
     */
    public function list_ville(VilleRepository $repository)
    {
        $data=new AfficherData();
        $form=$this->createForm(AfficherData::class,$data);
        $villes=$repository->findSearch();

        return $this->render('ville/list_ville.html.twig', ['villes'=>$villes,'form'=>$form->createView()]);
    }

    /**
     * @Route("/modifier_ville/{id}", name="modifier_ville")
     */
    public function modifier_ville($id,Request $request,EntityManagerInterface $em){

        $repo=$this->getDoctrine()->getRepository(Ville::class);
        $laVille=$repo->find($id);
        $ville=new Ville();
        $villeForm = $this->createForm(VilleType::class,$ville);

        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()){
            $laVille->setNom($ville->getNom());
            $laVille->setCodePostal($ville->getCodePostal());
            $em->persist($laVille);
            $em->flush();

            $this->addFlash("success","Modifications effectuées");
            return $this->redirectToRoute("accueil_ville");

        }
        return $this->render("ville/modifier_ville.html.twig",[
            "ville"=>$laVille,
            'page_name'=>'Modifier la Ville',
            "formulaire"=>$villeForm->createView(),
        ]);
    }

    /**
     * @Route("/delete_ville{id}",name="delete_ville",requirements={"id":"\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function delete_ville($id,EntityManagerInterface $em){

        $villeRepo= $this->getDoctrine()->getRepository(Ville::class);
       $ville=$villeRepo->find($id);
     $suppression=$villeRepo->delete_ville($ville);


        if(!$suppression){
            $this->addFlash("danger","Erreur, Vous ne pouvez malheureusement pas supprimer cette ville");
        }else{
            $this->addFlash("success","Cette ville a été supprimer avec succès");
        }

        return $this->redirectToRoute("accueil_ville");

    }



    /************************************************GESTION SITES************************************************/

    /**
     * @Route("/gestion/accueil_site", name="accueil_site")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function accueil_site(EntityManagerInterface $em,Request $request)
    {
        $user=$this->getUser();
        $filtre=new AfficherData();
        $siteRepo=$this->getDoctrine()->getRepository(Site::class);

        $filtreForm=$this->createForm(AfficherType::class,$filtre);
        $filtreForm->handleRequest($request);
        $sites=$siteRepo->findSearch($filtre);

        return $this->render('site/afficher_site.html.twig', [
            'page_name'=>'Gérer les Sites ',
            "form"=>$filtreForm->createView(),
            "sites"=>$sites,
        ]);
    }


    /**
     * @Route("/add_site", name="add_site")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function add_site(EntityManagerInterface $em,Request $request)
    {
        $site=new Site();
        $siteForm=$this->createForm(SiteType::class,$site);
        $siteForm->handleRequest($request);

        if($siteForm->isSubmitted() && $siteForm->isValid()) {
            $em->persist($site);
            $em->flush();
            $this->addFlash("success", " Site enregistrée avec succès !");
        }
        return $this->render('site/add_site.html.twig', ['siteForm'=>$siteForm->createView(),"page_name"=>"Ajouter Site"]);
    }


    /**
     * @Route("/list_site", name="list_site")
     * @param SiteRepository $repository
     * @return Response
     */
    public function list_site(SiteRepository $repository)
    {
        $data=new AfficherData();
        $form=$this->createForm(AfficherData::class,$data);
        $sites=$repository->findSearch();

        return $this->render('site/list_site.html.twig',
            ['sites'=>$sites,
                'form'=>$form->createView()
            ]);
    }


    /**
     * @Route("modifier_site/{id}", name="modifier_site")
     */
    public function modifier_site($id,Request $request,EntityManagerInterface $em){

        $repo=$this->getDoctrine()->getRepository(Site::class);
        $leSite=$repo->find($id);
        $site=new site();
        $siteForm = $this->createForm(SiteType::class,$site);

        $siteForm->handleRequest($request);

        if($siteForm->isSubmitted() && $siteForm->isValid()){
            $leSite->setNom($site->getNom());
            $em->persist($leSite);
            $em->flush();

            $this->addFlash("success","Modifications effectuées");
            return $this->redirectToRoute("accueil_site");

        }
        return $this->render("site/modifier_site.html.twig",[
            "site"=>$leSite,
            'page_name'=>'Modifier le Site',
            "formulaire"=>$siteForm->createView(),
        ]);
    }


    /**
     * @Route("/delete_site{id}",name="delete_site",requirements={"id":"\d+"})
     */
    public function delete_site($id,EntityManagerInterface $em){


        $siteRepo= $this->getDoctrine()->getRepository(Site::class);
        $site=$siteRepo->find($id);
        $suppression=$siteRepo->delete_site($site);
        if(!$suppression){
            $this->addFlash("danger","Erreur, Vous ne pouvez malheureusement pas supprimer ce site");
        }else{
            $this->addFlash("success","Ce Site a été supprimer avec succès");
        }

        return $this->redirectToRoute("accueil_site");

    }






}
