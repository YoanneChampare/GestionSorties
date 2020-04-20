<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Sortie;
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

        $sortieRepo=$this->getDoctrine()->getRepository(Sortie::class);

        $inscrit=$sortieRepo->isInscrit($user->getId());
        $filtreForm=$this->createForm(SearchType::class,$filtre);
        $filtreForm->handleRequest($request);
        dump($filtre);
        $sorties=$sortieRepo->findSearch($filtre);

       /* if($filtreForm->isSubmitted() && $filtreForm->isValid()){

        }*/

        return $this->render('participant/index.html.twig', [
            'page_name'=>'accueil',
            "form"=>$filtreForm->createView(),
            "sorties"=>$sorties,
            "inscrit"=>$inscrit,
            "user"=>$user
        ]);
    }


}
