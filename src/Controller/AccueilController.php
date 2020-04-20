<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\SortieParticipant;
use App\Form\SearchType;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AccueilController extends Controller
{

    /**
     * @Route("/", name="accueil")
     */
    public function portail(EntityManagerInterface $em,Request $request)
    {


        $user=$this->getUser();
        $filtre=new SearchData();
        $etat=new Etat();

        $sortieRepo=$this->getDoctrine()->getRepository(Sortie::class);
        $InscritRepo=$this->getDoctrine()->getRepository(SortieParticipant::class);
        $etatRepo=$this->getDoctrine()->getRepository(Etat::class);

        $inscrit=$InscritRepo->isInscrit($user->getId());
        $filtreForm=$this->createForm(SearchType::class,$filtre);

        $filtreForm->handleRequest($request);

        $sorties=$sortieRepo->findSearch($filtre,$user->getId());
        for($i=0;$i<sizeof($sorties);$i++){
            $et=$etatRepo->changeEtat($sorties[$i]);

                $sorties[$i]->setEtat($et);


        }


        return $this->render('participant/index.html.twig', [
            'page_name'=>'accueil',
            "form"=>$filtreForm->createView(),
            "sorties"=>$sorties,
            "inscrit"=>$inscrit,
            "user"=>$user
        ]);
    }


}
