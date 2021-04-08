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

    public function __toString()
    {
        // WEEK STRING
        $weekString = "";
        // All weeks
        if ($this->startWeek == 100) {
            $weekString = 'Toute l\'année ';

            $weekString = $this->weekExceptString($weekString);

        } else {
            // Single week
            if (is_null($this->endWeek)) {
                $weekString = 'La semaine ' . strval($this->startWeek) . ' ';

            // Multiple weeks
            } else {
                $weekString = 'De la semaine ' . strval($this->startWeek) . ' à la semaine ' . strval($this->endWeek);

                $weekString = $this->weekExceptString($weekString);
            }
        }
        $weekString .= ': ';

        // DAY STRING
        $dayString = "";
        $weekdays = [
            'lundi',
            'mardi',
            'mercredi',
            'jeudi',
            'vendredi'
        ];

        foreach ($this->weekdays as $value) {

            if ($value !== end($this->weekdays)) {
                $dayString .= $weekdays[$value] . ', ';
            } else {
                $dayString .= 'et ' . $weekdays[$value] . ' ';
            }
        }

        // TIME STRING
        $timeString = "";
        $times = [
            'de 8h00 à 9h30',
            'de 9h30 à 11h00',
            'de 11h00 à 12h30',
            'de 13h30 à 15h00',
            'de 15h00 à 16h30',
            'de 16h30 à 18h00',
            'de 18h00 à 19h30'
        ];

        foreach ($this->times as $value) {

            if ($value !== end($this->times)) {
                $timeString .= $times[$value - 1] . ', ';
            } else {
                $timeString .= 'et ' . $times[$value - 1];
            }
        }

        $string = $weekString . $dayString . $timeString;
        return $string;
    }


    private function weekExceptString($weekString) {
        // Except
        if (!is_null($this->ExceptStartWeek)) {
            $weekString .= ' (sauf ';

            // One week except
            if (is_null($this->ExceptEndWeek)) {
                $weekString .= 'la semaine ' . strval($this->ExceptStartWeek);

            // Multiple week except
            } else {
                $weekString .= 'de la semaine ' . strval($this->ExceptStartWeek) . ' jusqu\'à la semaine ' .  strval($this->ExceptEndWeek);
            }
        
            $weekString .= ') ';
        }
        return $weekString;
    }

    
}
