<?php

namespace App\Entity;

// use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=App\Repository\CommentsRepository::class)
 */
class Comments
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
    private $Content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Author;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $Created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userPicture;

    /**
     * @ORM\ManyToOne(targetEntity=Figures::class, inversedBy="comments", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Figure;

    /**
     * @ORM\ManyToOne(targetEntity=USer::class, inversedBy="comments", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(string $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->Created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $Created_at): self
    {
        $this->Created_at = $Created_at;

        return $this;
    }

    public function getUserPicture(): ?string
    {
        return $this->userPicture;
    }

    public function setUserPicture(string $userPicture): self
    {
        $this->userPicture = $userPicture;

        return $this;
    }

    public function getFigure(): ?Figures
    {
        return $this->Figure;
    }

    public function setFigure(?Figures $Figure): self
    {
        $this->Figure = $Figure;

        return $this;
    }

    public function getUser(): ?USer
    {
        return $this->user;
    }

    public function setUser(?USer $user): self
    {
        $this->user = $user;

        return $this;
    }
}
