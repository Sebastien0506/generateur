<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $horaire_debut = null;

    #[ORM\Column(length: 255)]
    private ?string $horaire_fin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHoraireDebut(): ?string
    {
        return $this->horaire_debut;
    }

    public function setHoraireDebut(string $horaire_debut): static
    {
        $this->horaire_debut = $horaire_debut;

        return $this;
    }

    public function getHoraireFin(): ?string
    {
        return $this->horaire_fin;
    }

    public function setHoraireFin(string $horaire_fin): static
    {
        $this->horaire_fin = $horaire_fin;

        return $this;
    }
}
