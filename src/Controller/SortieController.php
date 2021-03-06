<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\SortieParticipant;
use App\Form\AnnulerSortieType;
use App\Form\ProfilParticipantType;
use App\Form\SearchType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
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
        $user=$this->getUser();

        $sortieForm=$this->createForm(SortieType::class,$sortie);
        $etatRepo=$this->getDoctrine()->getRepository(Etat::class);

        $etat = $etatRepo->findOneBy([
            'libelle' => 'Créée'
        ]);

        $sortieForm->handleRequest($request);
        if($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            if($sortie->getDateHeureDebut()<=$sortie->getDateLimiteInscription()){
                $this->addFlash("warning", "Attention la date de la sortie doit être supérieure à la date limite d'inscription");
                return $this->redirectToRoute("sortie_add");
            }
            if($sortie->getDateLimiteInscription()<new \Datetime()){
                $this->addFlash("warning", "Attention la date limite d'inscription ne peut être inférieur à la date du jour");
                return $this->redirectToRoute("sortie_add");
            }

            $sortie->setSite($user->getSite());
            $sortie->setAuteur($user);
            $sortie->setEtat($etat);
            $em->persist($sortie);
            $em->flush();
            $this->addFlash("success", "Sortie enregistrée avec succès !");
            return $this->redirectToRoute("accueil");
        }
        return $this->render('sortie/add.html.twig', ['sortieForm'=>$sortieForm->createView(),"page_name"=>"Créer sortie"]);
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
     * @Route("/desister/{id}",name="desister",requirements={"id":"\d+"})
     */
    public function desister($id){

        $participantSRepo = $this->getDoctrine()->getRepository(SortieParticipant::class);
        $p_sortie = $participantSRepo->findAll();
        $iduser = $this->getUser()->getId();
        $user = $participantSRepo->Participant($iduser,$id);

        $supParticipant= $participantSRepo->sup_Participant($id,$iduser);
        $this->addFlash("success","Votre désistement a été pris en compte");
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/participer/{id}",name="participer",requirements={"id":"\d+"})
     */
    public function participer($id,EntityManagerInterface $em){

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        $sortieP = new SortieParticipant();
        $sortieP->setSortie($sortie);
        $sortieP->setParticipant($this->getUser());
        $em->persist($sortieP);
        $em->flush();
        $this->addFlash("success","Votre participation a été prise en compte");
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/afficherSortie/{id}",name="afficherSortie",requirements={"id":"\d+"})
     */
    public function afficherSortie($id,Request $request,EntityManagerInterface $em)
    {

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);

        $participantSRepo = $this->getDoctrine()->getRepository(SortieParticipant::class);
        $p_sortie = $participantSRepo->allParticipant($id);
        $iduser = $this->getUser()->getId();
        $user = $participantSRepo->Participant($iduser,$id);


        $dateMax=$sortie->getDateLimiteInscription();
        $datetime = date("Y-m-d H:i:s");


        if($user!=null){
            if (sizeof($p_sortie) >=$sortie->getNbInscriptionsMax() || $dateMax->format('Y-m-d H:i:s')<$datetime || $sortie->getEtat()->getLibelle()=='Annulée') {
                $etat = true;//rien
                $etat2 = false;
                $this->addFlash("danger", "Oups ! Il semble que la sortie soit complète ou que les inscriptions soient clôturées");
            }else{
                $etat = false;//désinscrire
                $etat2= false;
            }

        }
        else{
            if (sizeof($p_sortie) >=$sortie->getNbInscriptionsMax() || $dateMax->format('Y-m-d H:i:s')<$datetime || $sortie->getEtat()->getLibelle()=='Annulée') {
                $etat=true;//rien
                $etat2=false;
                $this->addFlash("danger","Oups ! Il semble que la sortie soit complète ou que les inscriptions soient clôturées");
            }
            else{
                $etat=true;
                $etat2=true;//inscription
            }
        }

        return $this->render('sortie/afficherSortie.html.twig',[
            "p_sortie"=>$p_sortie,
            "sortie"=>$sortie,
            "page_name"=>"Afficher sortie",
            "etat"=>$etat,
            "etat2"=>$etat2
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

        $organisteur=$this->getUser();
        $sortieAnnulerForm=$this->createForm(AnnulerSortieType::class,$sortie);
        $sortieAnnulerForm->handleRequest($request);

        if($sortieAnnulerForm->isSubmitted() ){
            $sortie->setInfosSortie($sortieAnnulerForm['infosSortie']->getData());
            $etatRepo = $this->getDoctrine()->getRepository(Etat::class);
            $endEtat = $etatRepo ->findOneBy(["libelle"=>'Annulée']);
            $sortie->setEtat($endEtat);
            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute("accueil");
        }


        return $this->render('sortie/annulerSortie.html.twig',[
            "sortie"=>$sortie,
            "page_name"=>"Annulation Sortie",
            "organisateur"=>$organisteur,
            "sortieAnnulerForm"=>$sortieAnnulerForm->createView()
        ]);
    }

    /**
     * @Route("/updateSortie/{id}",name="updateSortie")
     */
    public function modifierSortie($id,EntityManagerInterface $em,Request $request){

        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie= $sortieRepo->find($id);
        $laSortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class,$laSortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            if($laSortie->getDateHeureDebut()<=$laSortie->getDateLimiteInscription()){
                $this->addFlash("warning", "Attention la date de la sortie doit être supérieure à la date limite d'inscription");
                return $this->redirectToRoute("updateSortie",[
                    'id'=>$id
                ]);
            }
            if($laSortie->getDateLimiteInscription()<new \Datetime()){
                $this->addFlash("warning", "Attention la date limite d'inscription ne peut être inférieur à la date du jour");
                return $this->redirectToRoute("updateSortie",[
                    'id'=>$id
                ]);
            }
            $sortie->setNom($laSortie->getNom());
            $sortie->setDateHeureDebut($laSortie->getDateHeureDebut());
            $sortie->setDuree($laSortie->getDuree());
            $sortie->setDateLimiteInscription($laSortie->getDateLimiteInscription());
            $sortie->setNbInscriptionsMax($laSortie->getNbInscriptionsMax());
            $sortie->setInfosSortie($laSortie->getInfosSortie());
            $sortie->setEtat($laSortie->getEtat()->getId());
            $em->persist($sortie);
            $em->flush();
            $this->addFlash("success","Modification effectuée");
            return $this->redirectToRoute("accueil");
        }

        return $this->render('sortie/modifierSortie.html.twig',[
            "sortieForm"=>$sortieForm->createView(),
            "sortie"=>$sortie,
            "page_name"=>"Modifier Sortie"
        ]);
    }


    /**
     * @Route("/profilParticipant/{id}",name="profilParticipant",requirements={"id":"\d+"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param Participant $profilParticipant
     * @return Response
     */
    public function profilParticipant($id,EntityManagerInterface $em,Request $request, Participant $profilParticipant){
       $profilParticipantForm = $this->createForm(profilParticipantType::class,$profilParticipant);
       $profilParticipantForm ->handleRequest($request);
       $repo=$this->getDoctrine()->getRepository(Participant::class);
       $participant=$repo->find($id);

          if( $profilParticipantForm ->isSubmitted()){
              $em->persist($profilParticipant);
             $em->flush();
         }

         return $this->render('sortie/profilParticipant.html.twig',[
            "profilParticipant"=>$profilParticipant,
             "pa"=>$participant,
             "page_name"=>"Profil Participant",
            "profilParticipantForm"=> $profilParticipantForm ->createView()]);
      }

    /**
     * @Route("/publier/{id}",name="publier",requirements={"id":"\d+"})
     */

    public function publier($id,EntityManagerInterface $em){
        $sortieRepo=$this->getDoctrine()->getRepository(Sortie::class);
        $sortie=$sortieRepo->find($id);

        if ($sortie->getDateLimiteInscription()< new \Datetime()){
            $this->addFlash("danger","Une sortie ne peut être publiée si la date limite d'insciption est dépassée");
        }else{
            $sortie->setIsPublished(true);

            $em->persist($sortie);
            $em->flush();
        }

        return $this->redirectToRoute("accueil");



    }


}
