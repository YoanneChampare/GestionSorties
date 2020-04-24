<?php

namespace App\Repository;

use App\Data\AfficherData;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
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
     */

    public function findSearch(AfficherData $search) //,$idUser
    {
        $query = $this
            ->createQueryBuilder('s')
            ->select('s');
          //  ->setParameter('idUser', $idUser);


        if (!empty($search->motCle)) {
            $query = $query
                ->andWhere('s.nom LIKE :motCle')
                ->setParameter('motCle', "%{$search->motCle}%");
        }
        return $query->getQuery()->getResult();
    }


    public function CompareSiteP(Site $site){
        $test= $this->getEntityManager()->getRepository(Participant::class)->findBySite($site);
        return empty($test);
    }

    public function CompareSiteS(Site $site){
        $test2= $this->getEntityManager()->getRepository(Sortie::class)->findBySite2($site);
        return empty($test2);
    }

    public function delete_site(Site $site)
    {
        $em = $this->getEntityManager();
        //Verification si le site est liee a participant
        $CompareSiteP =$this->CompareSiteP($site);

        //Verification si le site est liee a sortie
        $CompareSiteS =$this->CompareSiteS($site);
        if (!$CompareSiteP) {
            $delete_site = false;
        }elseif (!$CompareSiteS){
            $delete_site = false;
        } else {
            //Mise en cache (commit)
            try {
                $em->remove($site);
                $em->flush();
                $delete_site = true;
            } catch (ORMException $e) {
                echo("Erreur");
            }


        }
        return $delete_site;
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
