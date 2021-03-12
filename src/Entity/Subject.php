<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubjectRepository::class)
 */
class Subject
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PPN;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CA;

    /**
     * @ORM\Column(type="smallint")
     */
    private $semester;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPPN(): ?string
    {
        return $this->PPN;
    }

    public function setPPN(string $PPN): self
    {
        $this->PPN = $PPN;

        return $this;
    }

    public function getCA(): ?string
    {
        return $this->CA;
    }

    public function setCA(?string $CA): self
    {
        $this->CA = $CA;

        return $this;
    }

    public function getSemester(): ?int
    {
        return $this->semester;
    }

    public function setSemester(int $semester): self
    {
        $this->semester = $semester;

        return $this;
    }
}
