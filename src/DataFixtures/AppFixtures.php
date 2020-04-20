<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $nomSite= array ('Nantes','Rennes','Niort');
        $nomVilles= array ('Nantes','Rennes','Niort','Saint-Herblain','Orvault','Paris');
        $cpo=array('44000','52000','42100','44800','44253','75000');



        for ($i = 0; $i < 4; $i++) {
            $sortie = new Sortie();
            $sortie->setNom('Sortie '.$i);
            $sortie->setDateHeureDebut(new \DateTime());
            $sortie->setDateLimiteInscription(new \DateTime());
            $sortie->setNbInscriptionsMax(10);
            $sortie->setDuree(new\DateTime());
            $sortie->setEtat("ouverte pour test");

            $manager->persist($sortie);
        }

        for ($i = 0; $i < 3; $i++) {
            $site=new Site();
            $site->setNom($nomSite[$i]);
            $manager->persist($site);
        }

        for ($i = 0; $i < 5; $i++) {
            $ville=new Ville();
            $ville->setNom($nomVilles[$i]);
            $ville->setCodePostal($cpo[$i]);
            $manager->persist($ville);
        }

        for ($i = 0; $i < 5; $i++) {
            $lieu=new Lieu();
            $lieu->setNom('Lieu '.$i);
            $lieu->setRue('Rue '.$i);
            $lieu->setLatitude(rand(124.12,785.45));
            $lieu->setLongitude(rand(124.12,785.45));
            $manager->persist($lieu);
        }


            $user=new Participant();
            $user->setNom("DUPONT");
            $user->setPrenom("Bob");
            $user->setActif(true);
            $user->setAdministrateur(true);
            $user->setMail("bob@du");
            $user->setPseudo('Boby7');
            $user->setTelephone("0123456789");
            $user->setMdp("$2y$13$9jPhbqyaerj/90u7zjOiTO1qQuOcqN3y.Pd2B0fb5EFSA9HRfoxxi"); //yoyo
            $manager->persist($user);

        $user1=new Participant();
        $user1->setNom("MARTIN");
        $user1->setPrenom("Léa");
        $user1->setActif(true);
        $user1->setAdministrateur(false);
        $user1->setMail("lea@ma");
        $user1->setPseudo('Clarita');
        $user1->setTelephone("0987654321");
        $user1->setMdp("$2y$13$9jPhbqyaerj/90u7zjOiTO1qQuOcqN3y.Pd2B0fb5EFSA9HRfoxxi"); //yoyo
        $manager->persist($user1);


        $manager->flush();

    }


}
