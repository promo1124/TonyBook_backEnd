<?php

namespace App\Service;

use App\Entity\Message;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class MessageService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function sendMessage(Reservation $reservation, User $sender, User $reception, string $contenu): Message
    {
        $message = new Message();
        $message->setReservation($reservation);
        $message->setSender($sender);
        $message->setReception($reception);
        $message->setContenu($contenu);
        $message->setSendAt(new \DateTimeImmutable());

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }
}