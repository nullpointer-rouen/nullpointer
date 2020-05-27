<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $labeltag;

     /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Question", inversedBy="questiontag")
     */
    private $tagquestion;

    public function __construct()
    {
        $this->tagquestion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabeltag(): ?string
    {
        return $this->labeltag;
    }

    public function setLabeltag(string $labeltag): self
    {
        $this->labeltag = $labeltag;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getTagquestion(): Collection
    {
        return $this->tagquestion;
    }

    public function addTagquestion(Question $tagquestion): self
    {
        if (!$this->tagquestion->contains($tagquestion)) {
            $this->tagquestion[] = $tagquestion;
        }

        return $this;
    }

    public function removeTagquestion(Question $tagquestion): self
    {
        if ($this->tagquestion->contains($tagquestion)) {
            $this->tagquestion->removeElement($tagquestion);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->labeltag;
    }

    
}
