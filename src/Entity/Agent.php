<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
class Agent extends Person
{
    #[ORM\ManyToOne(targetEntity: Agency::class, inversedBy: 'agents')]
    private null|Agency $agency;

    public function getAgency(): ?Agency
    {
        return $this->agency;
    }

    public function setAgency(null|Agency $agency): self
    {
        $this->agency = $agency;
        return $this;
    }

}
