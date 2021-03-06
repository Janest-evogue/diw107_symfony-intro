<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * la classe est liée à une table en bdd
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * clé primaire
     * @ORM\Id()
     * auto-increment
     * @ORM\GeneratedValue()
     * de type integer
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * varchar(20) NOT NULL en bdd
     * @ORM\Column(type="string", length=20)
     */
    private $pseudo;

    /**
     * varchar(255) NOT NULL unique en bdd
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * date nullable en bdd
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthdate;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }
}
