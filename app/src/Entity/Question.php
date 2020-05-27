<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $titlequestion;

    /**
     * @ORM\Column(type="text")
     */
    private $bodyquestion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datequestion;

    /**
     * @ORM\Column(type="integer", options={"default" : 10})
     */
    private $notequestion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reponse", mappedBy="question")
     * @ORM\OrderBy({"valide" = "DESC","datereponse" = "DESC"})
     */
    private $reponses;

    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="tagquestion", cascade={"persist"})
     */
    private $questiontag;

    /**
     * @ORM\Column(type="boolean")
     */
    private $resolve;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notequestion", mappedBy="idquestion")
     */
    private $notequestions;

    /**
     * @ORM\Column(type="integer")
     */
    private $vu;

    

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->questiontag = new ArrayCollection();
        $this->resolve = false;
        $this->notequestions = new ArrayCollection();
        $this->vu = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitlequestion(): ?string
    {
        return $this->titlequestion;
    }

    public function setTitlequestion(string $titlequestion): self
    {
        $this->titlequestion = $titlequestion;

        return $this;
    }

    public function getBodyquestion(): ?string
    {
        return $this->bodyquestion;
    }

    public function setBodyquestion(string $bodyquestion): self
    {
        $this->bodyquestion = $bodyquestion;

        return $this;
    }

    public function getDatequestion(): ?\DateTimeInterface
    {
        return $this->datequestion;
    }

    public function setDatequestion(\DateTimeInterface $datequestion): self
    {
        $this->datequestion = $datequestion;

        return $this;
    }

    public function getNotequestion(): ?int
    {
        return $this->notequestion;
    }

    public function setNotequestion(int $notequestion): self
    {
        $this->notequestion = $notequestion;

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

    
    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getQuestiontag(): Collection
    {
        return $this->questiontag;
    }

    public function addQuestiontag(Tag $questiontag): self
    {
        if (!$this->questiontag->contains($questiontag)) {
            $this->questiontag[] = $questiontag;
            $questiontag->addTagquestion($this);
        }

        return $this;
    }

    public function removeQuestiontag(Tag $questiontag): self
    {
        if ($this->questiontag->contains($questiontag)) {
            $this->questiontag->removeElement($questiontag);
            $questiontag->removeTagquestion($this);
        }

        return $this;
    }

    public function getResolve(): ?bool
    {
        return $this->resolve;
    }

    public function setResolve(bool $resolve): self
    {
        $this->resolve = $resolve;

        return $this;
    }

    /**
     * @return Collection|Notequestions[]
     */
    public function getNotequestions(): Collection
    {
        return $this->notequestions;
    }

    public function addNotequestion(Notequestion $notequestion): self
    {
        if (!$this->notequestions->contains($notequestion)) {
            $this->notequestions[] = $notequestion;
            $notequestion->setIdquestion($this);
        }

        return $this;
    }

    public function removeNotequestion(Notequestion $notequestion): self
    {
        if ($this->notequestions->contains($notequestion)) {
            $this->notequestions->removeElement($notequestion);
            // set the owning side to null (unless already changed)
            if ($notequestion->getIdquestion() === $this) {
                $notequestion->setIdquestion(null);
            }
        }

        return $this;
    }

    public function getVu(): ?int
    {
        return $this->vu;
    }

    public function setVu(int $vu): self
    {
        $this->vu = $vu;

        return $this;
    }


    
}
