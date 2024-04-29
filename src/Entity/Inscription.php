<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'inscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participant $idParticipant = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'inscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sortie $idSortie = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->idParticipant;
    }

    public function setIdParticipant(?Participant $idParticipant): static
    {
        $this->idParticipant = $idParticipant;

        return $this;
    }

    public function getIdSortie(): ?Sortie
    {
        return $this->idSortie;
    }

    public function setIdSortie(?Sortie $idSortie): static
    {
        $this->idSortie = $idSortie;

        return $this;
    }
}
