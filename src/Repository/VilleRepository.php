<?php

namespace App\Repository;

use App\Data\AfficherData;
use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{


    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }

    /**
     * Récupere les villes en lien avec les recherches
     * @param AfficherData $search
     */

    public function findSearch(AfficherData $search) //,$idUser
    {
        $query = $this
            ->createQueryBuilder('s')
            ->select('s');
           // ->setParameter('idUser', $idUser);


        if (!empty($search->motCle)) {
            $query = $query
                ->andWhere('s.nom LIKE :motCle')
                ->setParameter('motCle', "%{$search->motCle}%");
        }
        return $query->getQuery()->getResult();
    }





    public function CompareVille(Ville $ville){
        $test= $this->getEntityManager()->getRepository(Lieu::class)->findByVille($ville);
        return empty($test);
    }




    public function delete_ville(Ville $ville)
    {
        $em = $this->getEntityManager();
        //Verification si la ville est liee a un lieu
        $CompareVille =$this->CompareVille($ville);
        if (!$CompareVille) {
            $delete_Ville = false;
        } else {
            //Mise en cache (commit)
            try {
                $em->remove($ville);
                $em->flush();
                $delete_Ville = true;
            } catch (ORMException $e) {
                echo("Erreur");
            }


        }
        return $delete_Ville;
    }





    public function Delete_Villes($id){
        $em = $this->getEntityManager();

        if(!$em){
            $query=false;
        }else{
            $dql = "DELETE FROM App\Entity\Ville v WHERE  v.id=$id";
            $query = $em->createQuery($dql);
        }

        return  $query->getResult();
    }
    // /**
    //  * @return Ville[] Returns an array of Ville objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ville
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
