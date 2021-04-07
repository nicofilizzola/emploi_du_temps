<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $start;

    /**
     * @ORM\Column(type="date")
     */
    private $until;

    /**
     * @ORM\OneToMany(targetEntity=Day::class, mappedBy="session", orphanRemoval=true)
     */
    private $days;

    /**
     * @ORM\OneToMany(targetEntity=Attribution::class, mappedBy="session", orphanRemoval=true)
     */
    private $attributions;

    /**
     * @ORM\OneToMany(targetEntity=Preference::class, mappedBy="session", orphanRemoval=true)
     */
    private $preferences;

    /**
     * @ORM\OneToMany(targetEntity=EquipmentRequest::class, mappedBy="session", orphanRemoval=true)
     */
    private $equipmentRequests;


    public function __construct()
    {
        $this->days = new ArrayCollection();
        $this->attributions = new ArrayCollection();
        $this->preferences = new ArrayCollection();
        $this->equipmentRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getUntil(): ?\DateTimeInterface
    {
        return $this->until;
    }

    public function setUntil(\DateTimeInterface $until): self
    {
        $this->until = $until;

        return $this;
    }

    /**
     * @return Collection|Day[]
     */
    public function getDays(): Collection
    {
        return $this->days;
    }

    public function addDay(Day $day): self
    {
        if (!$this->days->contains($day)) {
            $this->days[] = $day;
            $day->setSession($this);
        }

        return $this;
    }

    public function removeDay(Day $day): self
    {
        if ($this->days->removeElement($day)) {
            // set the owning side to null (unless already changed)
            if ($day->getSession() === $this) {
                $day->setSession(null);
            }
        }

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
            $attribution->setSession($this);
        }

        return $this;
    }

    public function removeAttribution(Attribution $attribution): self
    {
        if ($this->attributions->removeElement($attribution)) {
            // set the owning side to null (unless already changed)
            if ($attribution->getSession() === $this) {
                $attribution->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Preference[]
     */
    public function getPreferences(): Collection
    {
        return $this->preferences;
    }

    public function addPreference(Preference $preference): self
    {
        if (!$this->preferences->contains($preference)) {
            $this->preferences[] = $preference;
            $preference->setSession($this);
        }

        return $this;
    }

    public function removePreference(Preference $preference): self
    {
        if ($this->preferences->removeElement($preference)) {
            // set the owning side to null (unless already changed)
            if ($preference->getSession() === $this) {
                $preference->setSession(null);
            }
        }

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
            $equipmentRequest->setSession($this);
        }

        return $this;
    }

    public function removeEquipmentRequest(EquipmentRequest $equipmentRequest): self
    {
        if ($this->equipmentRequests->removeElement($equipmentRequest)) {
            // set the owning side to null (unless already changed)
            if ($equipmentRequest->getSession() === $this) {
                $equipmentRequest->setSession(null);
            }
        }

        return $this;
    }
}
