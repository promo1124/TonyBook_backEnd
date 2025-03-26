<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['reservation:read'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $datetime = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Reservation $reservation = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $sender = null;

    #[ORM\ManyToOne(inversedBy: 'receivedMessages')]
    private ?User $reception = null;

    #[Groups(['reservation:read'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $sendAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDatetime(): ?\DateTimeImmutable
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeImmutable $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReception(): ?User
    {
        return $this->reception;
    }

    public function setReception(?User $reception): static
    {
        $this->reception = $reception;

        return $this;
    }

    public function getSendAt(): ?\DateTimeImmutable
    {
        return $this->sendAt;
    }

    public function setSendAt(\DateTimeImmutable $sendAt): static
    {
        $this->sendAt = $sendAt;

        return $this;
    }
}
