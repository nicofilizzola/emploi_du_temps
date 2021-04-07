<?php

namespace App\Entity;

use App\Repository\EquipmentRequestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipmentRequestRepository::class)
 */
class EquipmentRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $tds = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $tps = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="equipmentRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="equipmentRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=equipment::class, inversedBy="equipmentRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipment;

    /**
     * @ORM\ManyToOne(targetEntity=Subject::class, inversedBy="equipmentRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subject;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTds(): ?array
    {
        return $this->tds;
    }

    public function setTds(?array $tds): self
    {
        $this->tds = $tds;

        return $this;
    }

    public function getTps(): ?array
    {
        return $this->tps;
    }

    public function setTps(?array $tps): self
    {
        $this->tps = $tps;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEquipment(): ?equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?equipment $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }
}
