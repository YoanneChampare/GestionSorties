<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findSearch(SearchData $search){
        $query=$this
            ->createQueryBuilder('s')
            ->select('s,i')
            ->join('s.site','i');
            if(!empty($search->motCle)){
                $query=$query
                    ->andWhere('s.nom LIKE :motCle')
                    ->setParameter('motCle',"%{$search->motCle}%");
            }

            if(!empty ($search->nSite)){
                $query=$query

                    ->andWhere('i.id IN (:nSite)')
                    ->setParameter('nSite',$search->nSite);
            }

            if(!empty($search->datemin)){

            }


        return $query->getQuery()->getResult();
    }

    public function isInscrit($id){
        $em = $this->getEntityManager();
        $dql = "SELECT p
        FROM App\Entity\SortieParticipant p
        Join p.sortie s  WHERE p.id=$id";
        $query = $em->createQuery($dql);
        return  $query->getResult();

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


