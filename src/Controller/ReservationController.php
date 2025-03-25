<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationsRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReservationController extends AbstractController
{

    /*****************INDEX*******************/
    /*****************************************/
    #[Route('/reservation', methods: ['GET'])]
    public function getReservation(ReservationsRepository $reservationsRepository, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($reservationsRepository->findAll(), 'json', ['groups' => 'reservation:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    /*****************SHOW*******************/
    /*****************************************/
    #[Route('/reservation/{id}', methods: ['GET'])]
public function showReservation(Reservation $reservation, SerializerInterface $serializer): Response
{
    $data = [
        'id' => $reservation->getId(),
        'clientName' => $reservation->getClientName(),
        'dateReservation' => $reservation->getDateReservation()->format('Y-m-d H:i:s'),
        'status' => $reservation->getStatus(),
    ];

    $json = $serializer->serialize($data, 'json', ['groups' => 'reservation:read']);
    return new JsonResponse($json, Response::HTTP_OK, [], true);
}

    /*****************CREATE*******************/
    /*****************************************/
    #[Route('/reservation/new', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager, MailService $mailService): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['clientName']) || !isset($data['dateReservation'])) {
            return new JsonResponse(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        $reservation = new Reservation();
        $reservation->setClientName($data['clientName']);
        $reservation->setDateReservation(new \DateTime($data['dateReservation']));
        $reservation->setStatus($data['status'] ?? 'En attente');

        $entityManager->persist($reservation);
        $entityManager->flush();

        // Envoi d'un email de confirmation si l'email est renseigné
        if (isset($data['email'])) {
            $mailService->sendReservationEmail([
                'clientName' => $data['clientName'],
                'email' => $data['email'],
                'dateReservation' => $data['dateReservation'],
            ]);
        }

        return new JsonResponse(['message' => 'Réservation ajoutée avec succès'], Response::HTTP_OK);
    }

    /*****************UPDATE*******************/
    /*****************************************/
    #[Route('/reservation/{id}', methods: ['PUT'])]
    public function updateReservation(Reservation $reservation, Request $request, EntityManagerInterface $entityManager, MailService $mailService): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['clientName'])) {
            $reservation->setClientName($data['clientName']);
        }
        if (isset($data['dateReservation'])) {
            $reservation->setDateReservation(new \DateTime($data['dateReservation']));
        }
        if (isset($data['status'])) {
            $reservation->setStatus($data['status']);
            $reservation->setStatus($data['status']);

            // Envoi d'un email si la réservation est annulée 
            if ($data['status'] === 'Annuler') {
                $mailService->sendCancelReservationEmail([
                    'clientName' => $reservation->getClientName(),
                    'email' => $data['email'],
                    'dateReservation' => $reservation->getDateReservation()->format('Y-m-d H:i:s'),
                ]);
            }
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Réservation mise à jour'], Response::HTTP_OK);
    }

    /*****************DELETE*******************/
    /*****************************************/
    #[Route('/reservation/{id}', methods: ['DELETE'])]
    public function deleteReservation(Reservation $reservation, EntityManagerInterface $entityManager, MailService $mailService): Response
    {
        // Envoi d'un email si la réservation est annulée avant suppression
        $mailService->sendCancelReservationEmail([
            'clientName' => $reservation->getClientName(),
            'email' => 'email_du_client@exemple.com', // Remplacez par l'email réel
            'dateReservation' => $reservation->getDateReservation()->format('Y-m-d H:i:s'),
        ]);
        $entityManager->remove($reservation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Réservation Supprimée'], Response::HTTP_OK);
    }

}
