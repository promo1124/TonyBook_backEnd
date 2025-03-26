<?php

namespace App\Controller;

use App\Entity\Sejour;
use App\Repository\SejourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class SejourController extends AbstractController
{
    #[Route('/sejour', name: 'app_sejour')]
    public function getSejour(SejourRepository $sejourRepository, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($sejourRepository->findAll(), 'json', ['groups' => 'sejour:read']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/sejour/{id}', methods: ['GET'])]
    public function showReservation(Sejour $sejour, SerializerInterface $serializer): Response
    {
        $data = [
            'id' => $sejour->getId(),
            'dateDebut' => $sejour->getDateDebut()->format('Y-m-d H:i:s'),
            'dateFin' => $sejour->getDateFin()->format('Y-m-d H:i:s'),
            'nbJours' => $sejour->getNbJours()
        ];

        $json = $serializer->serialize($data, 'json', ['groups' => 'sejour:read']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/sejour/new', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['clientName']) || !isset($data['dateReservation'])) {
            return new JsonResponse(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        $sejour = new Sejour();
        $sejour->setDateDebut(new \DateTime($data['dateDebut']));
        $sejour->setDateFin(new \DateTime($data['dateFin']));

        $entityManager->persist($sejour);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Réservation ajoutée avec succès'], Response::HTTP_OK);
    }
}
