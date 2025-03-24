<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\SerializerInterface;


class ReservationController extends AbstractController
{
    #[Route('/reservation', methods: ['GET'])]
    public function getReservation(ReservationsRepository $reservationsRepository, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($reservationsRepository->findAll(), 'json');

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/reservation/{id}', methods: ['GET'])]
public function showReservation(Reservation $reservation, SerializerInterface $serializer): Response
{
    $data = [
        'id' => $reservation->getId(),
        'clientName' => $reservation->getClientName(),
        'dateReservation' => $reservation->getDateReservation()->format('Y-m-d H:i:s'),
        'status' => $reservation->getStatus(),
    ];

    $json = $serializer->serialize($data, 'json');
    return new JsonResponse($json, Response::HTTP_OK, [], true);
}

    #[Route('/reservation/new', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager): Response
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

        return new JsonResponse(['message' => 'Réservation ajoutée avec succès'], Response::HTTP_OK);
    }

    #[Route('/reservation/{id}', methods: ['PUT'])]
    public function updateReservation(Reservation $reservation, Request $request, EntityManagerInterface $entityManager): Response
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
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Réservation mise à jour'], Response::HTTP_OK);
    }

    #[Route('/reservation/{id}', methods: ['DELETE'])]
    public function deleteReservation(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($reservation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Réservation Supprimée'], Response::HTTP_OK);
    }

}
