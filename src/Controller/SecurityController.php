<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/api/login', name: 'api_login', methods: ['POST'])]
    public function login(AuthenticationUtils $authenticationUtils, SerializerInterface $serializer): JsonResponse
    {
        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            return new JsonResponse([
                'message' => 'Invalid credentials.',
                'error' => $error->getMessage(),
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Récupérer l'utilisateur authentifié
        $user = $this->getUser();

        // Vérifier si l'utilisateur est null (non authentifié)
        if (!$user) {
            return new JsonResponse([
                'message' => 'User not authenticated.',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Sérialiser l'utilisateur en JSON
        $userData = $serializer->serialize($user, 'json', ['groups' => ['user:read']]);

        return new JsonResponse($userData, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(path: '/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        // Symfony intercepte automatiquement cette route via le firewall
        return new JsonResponse(['message' => 'Logged out successfully.']);
    }
}