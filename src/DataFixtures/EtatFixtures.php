<?php


namespace App\DataFixtures;


use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture implements OrderedFixtureInterface
{



    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $statut= array ('Créée','Ouverte','Clôturée','Activité en cours','Passée','Annulée');


        for ($i = 0; $i < 5; $i++) {
            $etat=new Etat();
            $etat->setLibelle($statut[$i]);
            $manager->persist($etat);
        }
        $this->addReference('etat',$etat);
        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
        return 5;
    }
}