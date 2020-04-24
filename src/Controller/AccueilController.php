<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\SortieParticipant;
use App\Entity\Ville;
use App\Form\SearchType;
use App\Form\VilleType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/profile")
 */
class AccueilController extends Controller
{

    /**
     * @Route("/accueil", name="accueil")
     */
    public function portail(EntityManagerInterface $em,Request $request)
    {

        $user=$this->getUser();

        if(!$user->getActif()){

            $this->addFlash("danger","Votre compte est désactivé, veuillez contacter l'administrateur");
            return  $this->redirectToRoute("logout");


        }
        $today=new \Datetime();

        $filtre=new SearchData();
        $etat=new Etat();

        $sortieRepo=$this->getDoctrine()->getRepository(Sortie::class);
        $InscritRepo=$this->getDoctrine()->getRepository(SortieParticipant::class);
        $etatRepo=$this->getDoctrine()->getRepository(Etat::class);

        $inscrit=$InscritRepo->isInscrit($user->getId());

        $filtreForm=$this->createForm(SearchType::class,$filtre);

        $filtreForm->handleRequest($request);

        $sorties=$sortieRepo->findSearch($filtre,$user->getId());
        $quota=new ArrayCollection();
        foreach($sorties as $s){

            $quota->add($InscritRepo->allParticipant2($s->getId()));
            $sortieRepo->changeEtat($s,$user->getId());

        }


        return $this->render('participant/index.html.twig', [
            'page_name'=>'accueil',
            "form"=>$filtreForm->createView(),
            "sorties"=>$sorties,
            "inscrit"=>$inscrit,
            "user"=>$user,
            "quota"=>$quota,
            "today"=>new \Datetime()



        ]);
    }

    /**
     * @Route("/add_ville", name="add_ville2")
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


}
