<?php

namespace App\Entity;

use App\Repository\SejourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SejourRepository::class)]
class Sejour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column]
    private ?int $nbJours = null;

    #[ORM\ManyToOne(inversedBy: 'sejours')]
    private ?Produit $produit = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'sejour')]
    private Collection $reservations;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getDateDebut(): ?\DateTimeInterface { return $this->dateDebut; }
    public function setDateDebut(\DateTimeInterface $dateDebut): static {
        $this->dateDebut = $dateDebut;
        $this->updateNbJours();
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface { return $this->dateFin; }
    public function setDateFin(\DateTimeInterface $dateFin): static {
        $this->dateFin = $dateFin;
        $this->updateNbJours();
        return $this;
    }

    public function getNbJours(): ?int {
        return $this->dateDebut && $this->dateFin ? $this->dateDebut->diff($this->dateFin)->days : null;
    }

    public function updateNbJours(): void {
        $this->nbJours = $this->getNbJours();
    }

    public function getProduit(): ?Produit { return $this->produit; }
    public function setProduit(?Produit $produit): static { 
        $this->produit = $produit; 
        return $this; 
    }

    public function getReservations(): Collection { return $this->reservations; }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
