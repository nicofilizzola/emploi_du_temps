<?php

namespace App\Entity;

use App\Repository\AttributionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AttributionRepository::class)
 */
class Attribution
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $cmAmount;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $tdAmount;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $tpAmount;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="attributions")
     */
    private $session;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCmAmount(): ?int
    {
        return $this->cmAmount;
    }

    public function setCmAmount(?int $cmAmount): self
    {
        $this->cmAmount = $cmAmount;

        return $this;
    }

    public function getTdAmount(): ?int
    {
        return $this->tdAmount;
    }

    public function setTdAmount(?int $tdAmount): self
    {
        $this->tdAmount = $tdAmount;

        return $this;
    }

    public function getTpAmount(): ?int
    {
        return $this->tpAmount;
    }

    public function setTpAmount(?int $tpAmount): self
    {
        $this->tpAmount = $tpAmount;

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
}
