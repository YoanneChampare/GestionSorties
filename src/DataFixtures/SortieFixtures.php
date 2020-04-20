<?php


namespace App\DataFixtures;


use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture implements OrderedFixtureInterface
{



    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $lieuId=$this->getReference('lieu');
        $siteId=$this->getReference('site');
        $participantId=$this->getReference('participant');
        for ($i = 0; $i < 4; $i++) {
            $sortie = new Sortie();
            $sortie->setNom('Sortie '.$i);
            $sortie->setDateHeureDebut(new \DateTime());
            $sortie->setDateLimiteInscription(new \DateTime());
            $sortie->setNbInscriptionsMax(10);
            $sortie->setDuree(new\DateTime());
            $sortie->setEtat("ouverte pour test");
            $sortie->setSite($siteId);
            $sortie->setLieu($lieuId);
            $sortie->setParticipant();


            $manager->persist($sortie);
        }


        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
        return 6;
    }
}