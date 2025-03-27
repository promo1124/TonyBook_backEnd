<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ProduitRepository;
use App\Repository\ReservationsRepository;
use App\Repository\UserRepository;
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
    public function createReservation(Request $request, EntityManagerInterface $entityManager, MailService $mailService, ProduitRepository $produitRepository, UserRepository $userRepository): Response
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
    public function updateReservation(
        Reservation $reservation,
        Request $request,
        EntityManagerInterface $entityManager,
        MailService $mailService
    ): Response {
        $data = json_decode($request->getContent(), true);

        // Mettre à jour la réservation
        if (isset($data['dateReservation'])) {
            $reservation->setDateReservation(new \DateTime($data['dateReservation']));
        }
        if (isset($data['status'])) {
            $reservation->setStatus($data['status']);
            $reservation->setStatus($data['status']);

            // Envoi d'un email si la réservation est annulée 
            if ($data['status'] === 'Annuler') {
                $mailService->sendCancelReservationEmail([
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
            'email' => 'email_du_client@exemple.com', // Remplacez par l'email réel
            'dateReservation' => $reservation->getDateReservation()->format('Y-m-d H:i:s'),
        ]);
        $entityManager->remove($reservation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Réservation supprimée'], Response::HTTP_OK);
    }
}
