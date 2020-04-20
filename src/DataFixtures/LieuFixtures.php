<?php


namespace App\DataFixtures;


use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements OrderedFixtureInterface
{



    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $villeId=$this->getReference('ville');
        for ($i = 0; $i < 5; $i++) {
            $lieu=new Lieu();
            $lieu->setNom('Lieu '.$i);
            $lieu->setRue('Rue '.$i);
            $lieu->setLatitude(rand(124.12,785.45));
            $lieu->setLongitude(rand(124.12,785.45));
            $lieu->setVille($villeId);
            $manager->persist($lieu);
        }
        $this->addReference('lieu',$lieu->getId());


        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
        return 4;
    }
}