<?php

namespace App\Repository;

use App\Entity\SortieParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SortieParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method SortieParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method SortieParticipant[]    findAll()
 * @method SortieParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SortieParticipant::class);
    }

    // /**
    //  * @return SortieParticipant[] Returns an array of SortieParticipant objects
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
    public function findOneBySomeField($value): ?SortieParticipant
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function sup_Participant($ids,$idp){
        $em = $this->getEntityManager();
        $dql = "DELETE FROM App\Entity\SortieParticipant s WHERE s.sortie=$ids and s.participant=$idp";
        $query = $em->createQuery($dql);
        return  $query->getResult();
    }

    public function Participant($id,$ids){
        $em = $this->getEntityManager();
        $dql ="SELECT s FROM App\Entity\SortieParticipant s
        WHERE s.participant=$id and s.sortie=$ids";
        $query = $em->createQuery($dql);
        return  $query->getResult();
    }

    public function allParticipant($id){
        $em = $this->getEntityManager();
        $dql ="SELECT s FROM App\Entity\SortieParticipant s
        WHERE s.sortie=$id";
        $query = $em->createQuery($dql);
        return  $query->getResult();
    }

}
