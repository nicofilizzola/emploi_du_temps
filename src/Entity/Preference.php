<?php

namespace App\Entity;

use App\Repository\PreferenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PreferenceRepository::class)
 */
class Preference
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="preferences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="preferences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $startWeek;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $endWeek;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ExceptStartWeek;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ExceptEndWeek;

    /**
     * @ORM\Column(type="array")
     */
    private $weekdays = [];

    /**
     * @ORM\Column(type="array")
     */
    private $times = [];


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(?bool $state): self
    {
        $this->state = $state;

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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

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

    public function getStartWeek(): ?int
    {
        return $this->startWeek;
    }

    public function setStartWeek(int $startWeek): self
    {
        $this->startWeek = $startWeek;

        return $this;
    }

    public function getEndWeek(): ?int
    {
        return $this->endWeek;
    }

    public function setEndWeek(?int $endWeek): self
    {
        $this->endWeek = $endWeek;

        return $this;
    }

    public function getExceptStartWeek(): ?int
    {
        return $this->ExceptStartWeek;
    }

    public function setExceptStartWeek(?int $ExceptStartWeek): self
    {
        $this->ExceptStartWeek = $ExceptStartWeek;

        return $this;
    }

    public function getExceptEndWeek(): ?int
    {
        return $this->ExceptEndWeek;
    }

    public function setExceptEndWeek(?int $ExceptEndWeek): self
    {
        $this->ExceptEndWeek = $ExceptEndWeek;

        return $this;
    }

    public function getWeekdays(): ?array
    {
        return $this->weekdays;
    }

    public function setWeekdays(array $weekdays): self
    {
        $this->weekdays = $weekdays;

        return $this;
    }

    public function getTimes(): ?array
    {
        return $this->times;
    }

    public function setTimes(array $times): self
    {
        $this->times = $times;

        return $this;
    }
}
