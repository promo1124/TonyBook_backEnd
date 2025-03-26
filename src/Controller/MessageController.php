<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Service\MessageService;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

#[Route('/message', name: 'app_message')]
class MessageController extends AbstractController
{
    
    #[Route('/send', name: 'send', methods: ['POST'])]
    public function sendMessage(Request $request, EntityManager $entityManager, MessageService $messageService): Response
    {
        // Récupération des données de la requête
        $data = json_decode($request->getContent(), true);

        // Validation des données
        if (!isset($data['reservationId'], $data['senderId'], $data['receptionId'], $data['contenu'])) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        // Récupération des données de la requête par clé 
        $reservationId = $data['reservationId'];
        $senderId = $data['senderId'];
        $receptionId = $data['receptionId'];
        $contenu = $data['contenu'];

        //récupération des entités Reservation, User (sender et reception) par l'id
        $reservation = $entityManager->getRepository(Reservation::class)->find($reservationId);
        $sender = $entityManager->getRepository(User::class)->find($senderId);
        $reception = $entityManager->getRepository(User::class)->find($receptionId);

        // Envoi du message avec MessageService
        $message = $messageService->sendMessage($reservation, $sender, $reception, $contenu);

        // Renvoi de la réponse JSON avec le message créé
        return $this->json($message, Response::HTTP_CREATED);
    }
}

/*******UTILISATION DES MÉTHODES DU REPOSITORY******
 * 
 *  #[Route('/messages/reservation/{reservationId}', name: 'app_messages_reservation')]
    public function getMessagesByReservation(EntityManager $entityManager, int $reservationId, MessageRepository $messageRepository): Response
    {
        $reservation = $entityManager->getRepository(Reservation::class)->find($reservationId);

        if (!$reservation) {
            return $this->json(['error' => 'Reservation not found'], Response::HTTP_NOT_FOUND);
        }

        $messages = $messageRepository->findByReservation($reservation);

        return $this->json($messages, Response::HTTP_OK);
    }
*/

    


