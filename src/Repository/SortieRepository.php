<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * Récupere les sorties en lien avec les recherches
     */

    public function findSearch(SearchData $search,$idUser){
        $query=$this
            ->createQueryBuilder('s')
            ->select('s');


            if(!empty($search->motCle)){
                $query=$query
                    ->andWhere('s.nom LIKE :motCle')
                    ->setParameter('motCle',"%{$search->motCle}%");
            }

            if(!empty ($search->nSite)){
                $query=$query

                    ->andWhere('s.site IN (:nSite)')
                    ->setParameter('nSite',$search->nSite);
            }

            if(!empty($search->datemin)){
                $query=$query
                    ->andWhere('s.dateHeureDebut >:datemin')
                    ->setParameter('datemin',$search->datemin);
            }

            if(!empty($search->datemax)){
                $query=$query
                    ->andWhere('s.dateHeureDebut <=:datemax')
                    ->setParameter('datemax',$search->datemax);
            }

            if(!empty($search->sOrganisateur)){
                $query=$query
                    ->andWhere('s.auteur IN (:organisateur)')
                    ->setParameter('organisateur',$idUser);
            }

            if(!empty($search->sPasse)){
                $query=$query
                    ->andWhere('s.dateHeureDebut <:sortiePassee')
                    ->setParameter('sortiePassee',new \DateTime());
            }



            if(!empty($search->sInscrit)){
                $query=$query
                    ->join('s.jeParticipe','p')
                    ->andWhere("p.participant =:participant")
                    ->setParameter('participant',$idUser);
                    }

            if(!empty($search->sNonInscrit)){
                $query=$query
                    ->andWhere(" NOT IN (:nonparticipant)")
                    ->setParameter('nonparticipant',$idUser);

                }


        return $query->getQuery()->getResult();
    }


    public function isOrganisateur($idp){
        $em = $this->getEntityManager();
        $dql = "SELECT s
        FROM App\Entity\Sortie s
        WHERE s.sortie=$idp";
        $query = $em->createQuery($dql);
        return  $query->getResult();

    }

    public function changeEtat($id){
        $em=$this->getEntityManager();

        $sorties=$this->findAll();

        foreach($sorties as $key=>$s){
            if($s->getDateLimiteInscription() >= new \DateTime()) {
                $dql = "SELECT e.id FROM App\Entity\Etat e WHERE e.libelle='Ouverte'";
                $query1=$em->createQuery($dql);
                $etat=$query1->getResult();
            }
           if($s->getDateLimiteInscription()<= new \DateTime()) {
                $dql = "SELECT e.id FROM App\Entity\Etat e WHERE e.libelle='Passée'";
                $query1=$em->createQuery($dql);
                $etat=$query1->getResult();
            }
            /* if($sortie->getDateLimiteInscription()>= new \DateTime()) {
                 $dql = "SELECT e.id FROM App\Entity\Etat e WHERE e.libelle='Créée'";
             }*/


            foreach($etat as $e){


                dump($s);
                $req="UPDATE App\Entity\Sortie s SET s.etat=".$e["id"]."WHERE s.id=".$s->getId() ;
                $query2=$em->createQuery($req);
               return $query2->getResult();
            }


        }



    }



}


    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


