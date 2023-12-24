<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $jour = null;

    #[ORM\Column(length: 255)]
    private ?string $heure_debut = null;

    #[ORM\Column(length: 255)]
    private ?string $heure_fin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $jour_vacance_debut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $jour_vacance_fin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJour(): ?\DateTimeInterface
    {
        return $this->jour;
    }

    public function setJour(\DateTimeInterface $jour): static
    {
        $this->jour = $jour;

        return $this;
    }

    public function getHeureDebut(): ?string
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(string $heure_debut): static
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getHeureFin(): ?string
    {
        return $this->heure_fin;
    }

    public function setHeureFin(string $heure_fin): static
    {
        $this->heure_fin = $heure_fin;

        return $this;
    }

    public function getJourVacanceDebut(): ?string
    {
        return $this->jour_vacance_debut;
    }

    public function setJourVacanceDebut(?string $jour_vacance_debut): static
    {
        $this->jour_vacance_debut = $jour_vacance_debut;

        return $this;
    }

    public function getJourVacanceFin(): ?string
    {
        return $this->jour_vacance_fin;
    }

    public function setJourVacanceFin(?string $jour_vacance_fin): static
    {
        $this->jour_vacance_fin = $jour_vacance_fin;

        return $this;
    }
}
