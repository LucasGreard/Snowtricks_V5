<?php

namespace App\Entity;

use App\Repository\UserNewPWRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserNewPWRepository::class)
 */
class UserNewPW
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=USer::class, cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $Created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $_token;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->Created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $Created_at): self
    {
        $this->Created_at = $Created_at;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->_token;
    }

    public function setToken(?string $_token): self
    {
        $this->_token = $_token;

        return $this;
    }
}
