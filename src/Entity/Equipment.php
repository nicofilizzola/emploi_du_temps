<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipmentRepository::class)
 */
class Equipment
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
     * @ORM\OneToMany(targetEntity=EquipmentRequest::class, mappedBy="equipment", orphanRemoval=true)
     */
    private $equipmentRequests;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    public function __construct()
    {
        $this->equipmentRequests = new ArrayCollection();
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

    /**
     * @return Collection|EquipmentRequest[]
     */
    public function getEquipmentRequests(): Collection
    {
        return $this->equipmentRequests;
    }

    public function addEquipmentRequest(EquipmentRequest $equipmentRequest): self
    {
        if (!$this->equipmentRequests->contains($equipmentRequest)) {
            $this->equipmentRequests[] = $equipmentRequest;
            $equipmentRequest->setEquipment($this);
        }

        return $this;
    }

    public function removeEquipmentRequest(EquipmentRequest $equipmentRequest): self
    {
        if ($this->equipmentRequests->removeElement($equipmentRequest)) {
            // set the owning side to null (unless already changed)
            if ($equipmentRequest->getEquipment() === $this) {
                $equipmentRequest->setEquipment(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }
}
