<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservation
{
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;
    
        #[Groups(['reservation:read'])]
        #[ORM\Column(length: 255)]
        private ?string $clientName = null;


        #[Groups(['reservation:read'])]
        #[ORM\Column(type: Types::DATETIME_MUTABLE)]
        private ?\DateTimeInterface $dateReservation = null;
    
        #[Groups(['reservation:read'])]
        #[ORM\Column(length: 50)]
        private ?string $status = "En attente";
    
        #[ORM\ManyToOne(inversedBy: 'reservations')]
        private ?User $user = null;
    
        #[ORM\ManyToOne(inversedBy: 'reservations')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Sejour $sejour = null;
    
        #[ORM\ManyToOne(inversedBy: 'reservations')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Produit $produit = null;
    
        public function getId(): ?int { return $this->id; }

         public function getClientName(): ?string
        {
            return $this->clientName;
        }

        public function setClientName(string $clientName): static
        {
            $this->clientName = $clientName;

            return $this;
        }

        public function getDateReservation(): ?\DateTimeInterface { return $this->dateReservation; }
        public function setDateReservation(\DateTimeInterface $dateReservation): static { 
            $this->dateReservation = $dateReservation; 
            return $this; 
        }
    
        public function getStatus(): ?string { return $this->status; }
        public function setStatus(string $status): static { 
            $this->status = $status; 
            return $this; 
        }
    
        public function getUser(): ?User { return $this->user; }
        public function setUser(?User $user): static { 
            $this->user = $user; 
            return $this; 
        }
    
        public function getSejour(): ?Sejour { return $this->sejour; }
        public function setSejour(?Sejour $sejour): static { 
            $this->sejour = $sejour; 
            return $this; 
        }
    
        public function getProduit(): ?Produit { return $this->produit; }
        public function setProduit(?Produit $produit): static { 
            $this->produit = $produit; 
            return $this; 
        }

    }
    

