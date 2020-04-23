<?php

namespace App\Repository;

use App\Data\AfficherData;
use App\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Site|null find($id, $lockMode = null, $lockVersion = null)
 * @method Site|null findOneBy(array $criteria, array $orderBy = null)
 * @method Site[]    findAll()
 * @method Site[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Site::class);
    }

    /**
     * Récupere les sites en lien avec les recherches
     * @param AfficherData $search
     * @param $idUser
     */

    public function findSearch(AfficherData $search,$idUser)
    {
        $query = $this
            ->createQueryBuilder('s')
            ->select('s')
            ->setParameter('idUser', $idUser);


        if (!empty($search->motCle)) {
            $query = $query
                ->andWhere('s.nom LIKE :motCle')
                ->setParameter('motCle', "%{$search->motCle}%");
        }
    }
    // /**
    //  * @return Site[] Returns an array of Site objects
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
    public function findOneBySomeField($value): ?Site
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
