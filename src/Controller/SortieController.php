<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\SortieParticipant;
use App\Form\SearchType;
use App\Form\SortieType;
use App\Repository\LieuRepository;
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

    /**
     * @Route("/afficherSortie/{id}",name="afficherSortie",requirements={"id":"\d+"})
     */
    public function afficherSortie($id,Request $request,EntityManagerInterface $em)
    {

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        $participantSRepo = $this->getDoctrine()->getRepository(SortieParticipant::class);
        $p_sortie = $participantSRepo->findAll();
        $iduser = $this->getUser()->getId();
        $user = $participantSRepo->Participant($iduser);



        if($user!=null){
            $etat = false;//désinscrire
        }
        else{
            if (sizeof($p_sortie) >=$sortie->getNbInscriptionsMax()) {
                $etat=null;//rien
            }
            else{
                $etat=true;//inscription
            }
        }


        if ($request->getMethod() == 'POST') {

            if($etat==false){
                $supParticipant= $participantSRepo->sup_Participant($id,$iduser);
            }
            else{
                $sortieP = new SortieParticipant();
                $sortieP->setSortie($sortie);
                $sortieP->setParticipant($this->getUser());
                $em->persist($sortieP);
                $em->flush();
            }


        }

        return $this->render('sortie/afficherSortie.html.twig',[
            "p_sortie"=>$p_sortie,
            "sortie"=>$sortie,
            "page_name"=>"Sortie",
            "etat"=>$etat
        ]);
    }


    /**
     * @Route("/annulerSortie/{id}",name="annulerSortie",requirements={"id":"\d+"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param Sortie $sortie
     * @return Response
     */
    public function annulerSortie(EntityManagerInterface $em,Request $request, Sortie $sortie){

        $sortieAnnulerForm=$this->createForm(AnnulerSortieType::class,$sortie);
        $sortieAnnulerForm->handleRequest($request);


        return $this->render('sortie/afficherSortie.html.twig',[
            "sortie"=>$sortie,
            "page_name"=>"Sortie"
        ]);
    }


}
