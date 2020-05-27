<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReponseRepository")
 */

class Reponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min = 30, minMessage = "Body must be at least {{ limit }} characters long")
     * @Assert\NotBlank
     */
    private $bodyreponse;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datereponse;

    /**
     * @ORM\Column(type="integer")
     */
    private $notereponse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="boolean")
     */
    private $valide;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notereponse", mappedBy="idreponse")
     */
    private $notereponses;

   
    public function __construct()
    {
        $this->notereponse = 0;
        $this->valide= false;
        $this->notereponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBodyreponse(): ?string
    {
        return $this->bodyreponse;
    }

    public function setBodyreponse(string $bodyreponse): self
    {
        $this->bodyreponse = $bodyreponse;

        return $this;
    }

    public function getDatereponse(): ?\DateTimeInterface
    {
        return $this->datereponse;
    }

    public function setDatereponse(\DateTimeInterface $datereponse): self
    {
        $this->datereponse = $datereponse;

        return $this;
    }

    public function getNotereponse(): ?int
    {
        return $this->notereponse;
    }

    public function setNotereponse(int $notereponse): self
    {
        $this->notereponse = $notereponse;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * @return Collection|Notereponses[]
     */
    public function getNotereponses(): Collection
    {
        return $this->notereponses;
    }

    public function addNotereponse(Notereponse $notereponse): self
    {
        if (!$this->notereponses->contains($notereponse)) {
            $this->notereponses[] = $notereponse;
            $notereponse->setIdreponse($this);
        }

        return $this;
    }

    public function removeNotereponse(Notereponse $notereponse): self
    {
        if ($this->notereponses->contains($notereponse)) {
            $this->notereponses->removeElement($notereponse);
            // set the owning side to null (unless already changed)
            if ($notereponse->getIdreponse() === $this) {
                $notereponse->setIdreponse(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->bodyreponse;
    }

    
}
