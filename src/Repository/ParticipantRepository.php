<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    // /**
    //  * @return Participant[] Returns an array of Participant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function modifierProfil($id,$pseudo){
        $em = $this->getEntityManager();
        $dql="UPDATE 
        App\Entity\Participant p SET p.pseudo="."'$pseudo'"."
        WHERE p.id=$id";

        $query = $em->createQuery($dql);

        return $query->getResult();

    }

    public function Delete_Participant($id){
        $em = $this->getEntityManager();
        $dql = "DELETE FROM App\Entity\Participant p WHERE  p.id=$id";
        $query = $em->createQuery($dql);
        return  $query->getResult();
    }

    public function findBySite(Site $site){
        $result=$this->createQueryBuilder('s')
            ->select('s')
            ->where('s.site =:site')
            ->setParameter('site',$site->getId());

        return $result->getQuery()
            ->getArrayResult();
    }


}
