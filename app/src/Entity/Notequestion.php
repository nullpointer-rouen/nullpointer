<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotequestionRepository")
 */
class Notequestion
{


    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="idquestion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $iduser;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="notequestions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idquestion;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdquestion(): ?Question
    {
        return $this->idquestion;
    }

    public function setIdquestion(?Question $idquestion): self
    {
        $this->idquestion = $idquestion;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }
}
