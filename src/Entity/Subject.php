<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=Attribution::class, mappedBy="subject", orphanRemoval=true)
     */
    private $attributions;


    public function __construct()
    {
        $this->attributions = new ArrayCollection();
        //$this->setCode();
    }

    public function __toString()
    {
        $this->setCode();
        return $this->getCode();
    }

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

    /**
     * @return Collection|Attribution[]
     */
    public function getAttributions(): Collection
    {
        return $this->attributions;
    }

    public function addAttribution(Attribution $attribution): self
    {
        if (!$this->attributions->contains($attribution)) {
            $this->attributions[] = $attribution;
            $attribution->setSubject($this);
        }

        return $this;
    }

    public function removeAttribution(Attribution $attribution): self
    {
        if ($this->attributions->removeElement($attribution)) {
            // set the owning side to null (unless already changed)
            if ($attribution->getSubject() === $this) {
                $attribution->setSubject(null);
            }
        }

        return $this;
    }

    // Attribute not in database, only used for display
    private $code;

    public function getCode(){
        return $this->code;
    }

    public function setCode(){
        $this->code = $this->PPN . '_' . $this->semester;
    }
}
