<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FigureImgRepository::class)
 */
class FigureImg
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
    private $imgName;

    /**
     * @ORM\ManyToOne(targetEntity=Figures::class, inversedBy="figureImgs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $figureId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImgName(): ?string
    {
        return $this->imgName;
    }

    public function setImgName(string $imgName): self
    {
        $this->imgName = $imgName;

        return $this;
    }

    public function getFigureId(): ?Figures
    {
        return $this->figureId;
    }

    public function setFigureId(?Figures $figureId): self
    {
        $this->figureId = $figureId;

        return $this;
    }
}
