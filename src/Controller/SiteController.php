<?php

namespace App\Controller;

use App\Data\AfficherData;

use App\Entity\Site;
use App\Entity\Ville;
use App\Form\SiteType;
use App\Form\VilleType;
use App\Repository\SiteRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SiteController extends Controller
{

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

        return $this->render('ville/list_ville.html.twig',
            ['villes'=>$villes,
                'form'=>$form->createView()
            ]);
    }




    /**
     * @Route("/afficherVille/{id}",name="afficherVille",requirements={"id":"\d+"})
     * @param $id
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function afficherVille($id,Request $request,EntityManagerInterface $em)
    {

        $villeRepo = $this->getDoctrine()->getRepository(Ville::class);
        $ville = $villeRepo->find($id);

        return $this->render('ville/afficherVille.html.twig',[
            "page_name"=>"Ville",
            "ville"=>$ville,
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
     * @Route("/afficherSite/{id}",name="afficherSite",requirements={"id":"\d+"})
     * @param $id
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function afficherSite($id,Request $request,EntityManagerInterface $em)
    {

        $siteRepo = $this->getDoctrine()->getRepository(Site::class);
        $site = $siteRepo->find($id);

        return $this->render('site/afficherSite.html.twig',[
            "page_name"=>"Site",
            "site"=>$site,
        ]);
    }


}
