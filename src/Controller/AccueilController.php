<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\SortieParticipant;
use App\Form\SearchType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        $test=$user->getRoles();
        dump($test);
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



        ]);
    }


}
