<?php

namespace App\Entity;

use App\Repository\BoutiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

   

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'boutique')]
    private Collection $users;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $horaireDebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $horaireFin = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    

    

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addBoutique($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeBoutique($this);
        }

        return $this;
    }

    public function getHoraireDebut(): ?\DateTimeInterface
    {
        return $this->horaireDebut;
    }

    public function setHoraireDebut(\DateTimeInterface $horaireDebut): static
    {
        $this->horaireDebut = $horaireDebut;

        return $this;
    }

    public function getHoraireFin(): ?\DateTimeInterface
    {
        return $this->horaireFin;
    }

    public function setHoraireFin(\DateTimeInterface $horaireFin): static
    {
        $this->horaireFin = $horaireFin;

        return $this;
    }
}
