<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotereponseRepository")
 */
class Notereponse
{
 

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     *  @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="notereponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $iduser;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Reponse", inversedBy="notereponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idreponse;


    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getIduser(): ?Users
    {
        return $this->iduser;
    }

    public function setIduser(?Users $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdreponse(): ?Reponse
    {
        return $this->idreponse;
    }

    public function setIdreponse(?Reponse $idreponse): self
    {
        $this->idreponse = $idreponse;

        return $this;
    }
}
