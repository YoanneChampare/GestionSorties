<?php

namespace App\Controller;



use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LieuController extends Controller
{

    /**
     * @Route("/add_lieu", name="add_lieu")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function add_lieu(EntityManagerInterface $em,Request $request)
    {
        $lieu=new Lieu();
        $lieuForm=$this->createForm(LieuType::class,$lieu);
        $lieuForm->handleRequest($request);

        if($lieuForm->isSubmitted() && $lieuForm->isValid()) {
            $em->persist($lieu);
            $em->flush();
            $this->addFlash("success", " Lieu enregistrée avec succès !");
        }
        return $this->render('lieu/add_lieu.html.twig', ['lieuForm'=>$lieuForm->createView(),"page_name"=>"Ajouter Lieux"]);
    }


}
