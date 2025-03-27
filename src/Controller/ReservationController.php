<?php

namespace App\Controller;

use App\Entity\Reservation;

use App\Repository\ReservationsRepository;

use App\Service\MailService;
use App\Service\ReservationMailService;
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
    public function getReservations(ReservationsRepository $reservationsRepository, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($reservationsRepository->findAll(), 'json', ['groups' => 'reservation:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    /*****************SHOW*******************/
    /*****************************************/
    #[Route('/reservation/{id}', methods: ['GET'])]
    public function showReservation(Reservation $reservation, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($reservation, 'json', ['groups' => 'reservation:read']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /*****************CREATE*******************/
    /*****************************************/
    #[Route('/reservation/new', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager, MailService $mailService): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['produitId'])) {
            return new JsonResponse(['error' => 'Le produit est requis'], Response::HTTP_BAD_REQUEST);
        }
    
        $produit = $produitRepository->find($data['produitId']);
        if (!$produit) {
            return new JsonResponse(['error' => 'Produit non trouvé'], Response::HTTP_NOT_FOUND);
        }
    
        $user = $userRepository->find($data["user"]) ;

        
        // Créer une nouvelle réservation
        $reservation = new Reservation();
        $reservation->setDateDebut(new \DateTime($data['dateD']));
        $reservation->setDateFin(new \DateTime($data['dateF']));
        $reservation->setStatus($data['status'] ?? 'En attente');
        $reservation->setProduit($produit); // Associer le produit
        $reservation->setUser($user);
                
        $entityManager->persist($reservation);
        $entityManager->flush();

        // Envoi d'un email de confirmation si l'email est renseigné
        if (isset($data['email'])) {
            $mailService->sendReservationEmail([
                'email' => $data['email'],
                'dateReservation' => $data['dateReservation'],
            ]);
        }

        return new JsonResponse(['message' => 'Réservation ajoutée avec succès'], Response::HTTP_OK);
    }

    /*****************UPDATE*******************/
    /*****************************************/
    #[Route('/reservation/{id}', methods: ['PUT'])]
    public function updateReservation(Reservation $reservation, Request $request, EntityManagerInterface $entityManager, ReservationMailService $reservationMailService): Response
    {
        $data = json_decode($request->getContent(), true);
    
        // changement de statut de la réservation
        if ($reservation->getStatus() !== 'En attente') {
            return new JsonResponse(['message' => 'Réservation non modifiable'], Response::HTTP_BAD_REQUEST);
        }

        // Mise à jour du statut de la réservation
        if (isset($data['status'])) {
            //définit le statut
            $status = $data['status'];
            //Mise à jour du statut
            $reservation->setStatus($data['status']);
    
            // Envoi d'un email si la réservation est validée ou refusée
            if ($status === 'Validée') {
                $reservation->setStatus($status);
                $reservationMailService->validerReservation($reservation); // Envoi de l'email de validation
            } elseif ($status === 'Déclinée') {
                $reservation->setStatus($status);
                $reservationMailService->refuserReservation($reservation); // Envoi de l'email de rejet
                $entityManager->remove($reservation);
            } else {
                return new JsonResponse(['message' => 'Status invalide'], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse(['message' => 'Status manquant'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();
        return new JsonResponse(['message' => 'Réservation mise à jour'], Response::HTTP_OK);
    }



    /*****************DELETE*******************/
    /*****************************************/
    #[Route('/reservation/{id}', methods: ['DELETE'])]
    public function deleteReservation(Reservation $reservation, EntityManagerInterface $entityManager, ReservationMailService $reservationMailService): Response
    {
        // Envoi d'un email si la réservation est annulée avant suppression
        $reservationMailService->refuserReservation($reservation);

        $entityManager->remove($reservation);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Réservation supprimée'], Response::HTTP_OK);
    }
}
