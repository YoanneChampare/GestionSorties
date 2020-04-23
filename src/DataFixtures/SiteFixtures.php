<?php


namespace App\DataFixtures;


use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SiteFixtures extends Fixture implements OrderedFixtureInterface
{



    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $nomSite= array ('Nantes','Rennes','Niort');
        for ($i = 0; $i < 3; $i++) {
            $site=new Site();
            $site->setNom($nomSite[$i]);
            $manager->persist($site);
        }
        $this->addReference('site',$site);
        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
        return 1;
    }
}