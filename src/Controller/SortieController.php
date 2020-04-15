<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends Controller
{

    /**
     * @Route("/sortie", name="sortie")
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
}
