<?php

namespace App\Entity;

use App\Repository\VacanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VacanceRepository::class)]
class Vacance
{
    

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $jourDeDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $jourDeFin = null;

    #[ORM\ManyToOne(inversedBy: 'vacances')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true, )]
    private ?string $status = null;

    #[ORM\OneToOne(mappedBy: 'vacance', cascade: ['persist', 'remove'])]
    private ?Notification $notification = null;




   

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourDeDebut(): ?\DateTimeInterface
    {
        return $this->jourDeDebut;
    }

    public function setJourDeDebut(?\DateTimeInterface $jourDeDebut): static
    {
        $this->jourDeDebut = $jourDeDebut;

        return $this;
    }

    public function getJourDeFin(): ?\DateTimeInterface
    {
        return $this->jourDeFin;
    }

    public function setJourDeFin(?\DateTimeInterface $jourDeFin): static
    {
        $this->jourDeFin = $jourDeFin;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): static
    {
        // unset the owning side of the relation if necessary
        if ($notification === null && $this->notification !== null) {
            $this->notification->setVacance(null);
        }

        // set the owning side of the relation if necessary
        if ($notification !== null && $notification->getVacance() !== $this) {
            $notification->setVacance($this);
        }

        $this->notification = $notification;

        return $this;
    }

   

   

    
}
