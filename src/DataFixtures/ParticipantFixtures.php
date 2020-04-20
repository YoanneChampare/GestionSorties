<?php


namespace App\DataFixtures;


use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ParticipantFixtures extends Fixture implements OrderedFixtureInterface
{



    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $siteId=$this->getReference('site');

        $user=new Participant();
        $user->setNom("DUPONT");
        $user->setPrenom("Bob");
        $user->setActif(true);
        $user->setAdministrateur(true);
        $user->setMail("bob@du");
        $user->setPseudo('Boby7');
        $user->setTelephone("0123456789");
        $user->setMdp("$2y$13$9jPhbqyaerj/90u7zjOiTO1qQuOcqN3y.Pd2B0fb5EFSA9HRfoxxi"); //yoyo
        $user->setSite($siteId);
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
        $user1->setSite($siteId);
        $manager->persist($user1);

        $user2=new Participant();
        $user2->setNom("TOTO");
        $user2->setPrenom("Paul");
        $user2->setActif(false);
        $user2->setAdministrateur(false);
        $user2->setMail("paul@to");
        $user2->setPseudo('Paulo');
        $user2->setTelephone("0192837654");
        $user2->setMdp("$2y$13$9jPhbqyaerj/90u7zjOiTO1qQuOcqN3y.Pd2B0fb5EFSA9HRfoxxi"); //yoyo
        $user2->setSite($siteId);
        $manager->persist($user2);

        $this->addReference('participant',$user);

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
        return 3;
    }
}