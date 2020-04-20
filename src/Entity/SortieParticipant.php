<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SortieParticipantRepository")
 */
class SortieParticipant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participant",inversedBy="jeParticipe")
     */
    private $participant;

    /**
     *  @ORM\ManyToOne(targetEntity="App\Entity\Sortie",inversedBy="jeParticipe")
     */
    private $sortie;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * @param mixed $participant
     */
    public function setParticipant($participant)
    {
        $this->participant = $participant;
    }

    /**
     * @return mixed
     */
    public function getSortie()
    {
        return $this->sortie;
    }

    /**
     * @param mixed $sortie
     */
    public function setSortie($sortie)
    {
        $this->sortie = $sortie;
    }


}
