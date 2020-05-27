<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity(fields={"email"}, message="Email Existant")
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Regex(
     * pattern="/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",
     * message="not_valid_email"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $note;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="user")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reponse", mappedBy="user")
     */
    private $reponses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notereponse", mappedBy="iduser")
     */
    private $notereponses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notequestion", mappedBy="iduser")
     */
    private $notequestions;

    

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->reponses = new ArrayCollection();
      
        $this->note=1;
        $this->notereponses = new ArrayCollection();
        $this->idquestion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setUser($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getUser() === $this) {
                $question->setUser(null);
            }
        }

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
            $reponse->setUser($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getUser() === $this) {
                $reponse->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notereponse[]
     */
    public function getNotereponses(): Collection
    {
        return $this->notereponses;
    }

    public function addNotereponse(Notereponse $notereponse): self
    {
        if (!$this->notereponses->contains($notereponse)) {
            $this->notereponses[] = $notereponse;
            $notereponse->setIduser($this);
        }

        return $this;
    }

    public function removeNotereponse(Notereponse $notereponse): self
    {
        if ($this->notereponses->contains($notereponse)) {
            $this->notereponses->removeElement($notereponse);
            // set the owning side to null (unless already changed)
            if ($notereponse->getIduser() === $this) {
                $notereponse->setIduser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notequestion[]
     */
    public function getNotequestions(): Collection
    {
        return $this->notequestions;
    }

    public function addIdquestion(Notequestion $notequestion): self
    {
        if (!$this->notequestions->contains($notequestion)) {
            $this->idquestion[] = $notequestion;
            $idquestion->setIduser($this);
        }

        return $this;
    }

    public function removeIdquestion(Notequestion $idquestion): self
    {
        if ($this->notequestions->contains($notequestion)) {
            $this->notequestions->removeElement($notequestion);
            // set the owning side to null (unless already changed)
            if ($notequestion->getIduser() === $this) {
                $notequestion->setIduser(null);
            }
        }

        return $this;
    }

    
    
}
