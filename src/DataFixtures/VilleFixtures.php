<?php


namespace App\DataFixtures;



use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VilleFixtures extends Fixture implements OrderedFixtureInterface
{



    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $nomVilles= array ('Nantes','Rennes','Niort','Saint-Herblain','Orvault','Paris');
        $cpo=array('44000','52000','42100','44800','44253','75000');

        for ($i = 0; $i < 5; $i++) {
            $ville=new Ville();
            $ville->setNom($nomVilles[$i]);
            $ville->setCodePostal($cpo[$i]);
            $manager->persist($ville);
        }
        $this->addReference('ville',$ville);
        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
        return 2;
    }
}