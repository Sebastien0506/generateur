<?php

namespace App\Entity;

use App\Repository\BoutiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoutiqueRepository::class)]
class Boutique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_boutique = null;

    #[ORM\Column(length: 255)]
    private ?string $horaire_ouverture = null;

    #[ORM\Column(length: 255)]
    private ?string $horaire_fermeture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomBoutique(): ?string
    {
        return $this->nom_boutique;
    }

    public function setNomBoutique(string $nom_boutique): static
    {
        $this->nom_boutique = $nom_boutique;

        return $this;
    }

    public function getHoraireOuverture(): ?string
    {
        return $this->horaire_ouverture;
    }

    public function setHoraireOuverture(string $horaire_ouverture): static
    {
        $this->horaire_ouverture = $horaire_ouverture;

        return $this;
    }

    public function getHoraireFermeture(): ?string
    {
        return $this->horaire_fermeture;
    }

    public function setHoraireFermeture(string $horaire_fermeture): static
    {
        $this->horaire_fermeture = $horaire_fermeture;

        return $this;
    }
}
