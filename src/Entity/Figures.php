<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=App\Repository\FiguresRepository::class)
 */
class Figures
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $figureName;

    /**
     * @ORM\Column(type="text")
     */
    private $figureDescription;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $FigureGroup;

    /**
     * @ORM\Column(type="string", length=102)
     */
    private $figureAuthor;

    /**
     * @ORM\Column(type="datetime")
     */
    private $figureDateAdd;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $figureDateModif;

    /**
     * @ORM\OneToMany(targetEntity=FigureImg::class, mappedBy="figureId", 
     * orphanRemoval=true, cascade={"persist"})
     */
    private $figureImgs;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $figureVideo;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="figure", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->figureImgs = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFigureName(): ?string
    {
        return $this->figureName;
    }

    public function setFigureName(string $figureName): self
    {
        $this->figureName = $figureName;

        return $this;
    }

    public function getFigureDescription()
    {
        return $this->figureDescription;
    }

    public function setFigureDescription(string $figureDescription): self
    {
        $this->figureDescription = $figureDescription;
        return $this;
    }

    public function getFigureGroup(): ?string
    {
        return $this->FigureGroup;
    }

    public function setFigureGroup(string $FigureGroup): self
    {
        $this->FigureGroup = $FigureGroup;

        return $this;
    }

    public function getFigureAuthor(): ?string
    {
        return $this->figureAuthor;
    }

    public function setFigureAuthor(string $figureAuthor): self
    {
        $this->figureAuthor = $figureAuthor;

        return $this;
    }

    public function getFigureDateAdd(): ?\DateTimeInterface
    {
        return $this->figureDateAdd;
    }

    public function setFigureDateAdd(\DateTimeInterface $figureDateAdd): self
    {
        $this->figureDateAdd = new \DateTime();

        return $this;
    }

    public function getFigureDateModif(): ?\DateTimeInterface
    {
        return $this->figureDateModif;
    }

    public function setFigureDateModif(?\DateTimeInterface $figureDateModif): self
    {
        $this->figureDateModif = $figureDateModif;

        return $this;
    }

    /**
     * @return Collection|FigureImg[]
     */
    public function getFigureImgs(): Collection
    {
        return $this->figureImgs;
    }

    public function addFigureImg(FigureImg $figureImg): self
    {
        if (!$this->figureImgs->contains($figureImg)) {
            $this->figureImgs[] = $figureImg;
            $figureImg->setFigureId($this);
        }

        return $this;
    }

    public function removeFigureImg(FigureImg $figureImg): self
    {
        if ($this->figureImgs->removeElement($figureImg)) {
            // set the owning side to null (unless already changed)
            if ($figureImg->getFigureId() === $this) {
                $figureImg->setFigureId(null);
            }
        }

        return $this;
    }

    public function getFigureVideo()
    {

        // return explode(",", $this->figureVideo);
        return $this->figureVideo;
    }

    public function setFigureVideo($figureVideo): self
    {

        $this->figureVideo = $figureVideo;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setFigure($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getFigure() === $this) {
                $comment->setFigure(null);
            }
        }

        return $this;
    }
}
