<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Sortie;
use App\Form\SearchType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends Controller
{

    /**
     * @Route("/sortie_add", name="sortie_add")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function add(EntityManagerInterface $em,Request $request)
    {
        $sortie=new Sortie();
        $sortieForm=$this->createForm(SortieType::class,$sortie);

        $sortieForm->handleRequest($request);
        if($sortieForm->isSubmitted()){
            $em->persist($sortie);
            $em->flush();
        }
        return $this->render('sortie/add.html.twig', ['sortieForm'=>$sortieForm->createView()]);
    }


    /**
     * @Route("/sortie_list", name="sortie_list")
     * @param SortieRepository $repository
     * @return Response
     */
    public function list(SortieRepository $repository)
    {
       $data=new SearchData();
       $form=$this->createForm(SearchType::class,$data);
       $sorties=$repository->findSearch();

        return $this->render('sortie/list.html.twig',
            ['sorties'=>$sorties,
                'form'=>$form->createView()
            ]);
    }
}
