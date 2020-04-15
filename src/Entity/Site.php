<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 */
class Site
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $idSite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

//    /**
//     * @ORM\OnetoMany(targetEntity="App\Entity\Participant"  inverdedBy="sites"
//     */
//    private $participant;









    /**
     * @return mixed
     */
    public function getIdSite()
    {
        return $this->idSite;
    }

    /**
     * @param mixed $idSite
     */
    public function setIdSite($idSite)
    {
        $this->idSite = $idSite;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

//    /**
//     * @return mixed
//     */
//    public function getParticipant()
//   {
//        return $this->participant;
//    }



//      /**
//     * @param mixed $participant
//     */
//    public function setParticipant($participant)
//    {
//        $this->participant = $participant;
//    }




}
