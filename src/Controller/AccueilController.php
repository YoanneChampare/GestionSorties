<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Sortie;
use App\Form\SearchType;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(EntityManagerInterface $em)
    {
        $filtre=new SearchData();

        $sortieRepo=$this->getDoctrine()->getRepository(Sortie::class);
        $sorties=$sortieRepo->findAll();
        $inscrit=$sortieRepo->
        $filtreForm=$this->createForm(SearchType::class,$filtre);

        return $this->render('participant/index.html.twig', [
            'page_name'=>'accueil',
            "form"=>$filtreForm->createView(),
            "sorties"=>$sorties
        ]);
    }
}
