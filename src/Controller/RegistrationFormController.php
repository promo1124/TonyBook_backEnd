<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;


class RegistrationFormController extends AbstractController
{


    #[Route('/register', name: 'app_register', methods: ['POST'])]
 
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Récupération des données JSON envoyées par React
        $data = json_decode($request->getContent(), true);
 
        if (!$data) {
            return new JsonResponse(['message' => 'User not registered'], Response::HTTP_BAD_REQUEST);
        }

        /**
         * CONTRAINTE:
         * Vérifier si l'email existe déjà
         */
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
 
        if ($existingUser) {
            return new JsonResponse(['message' => 'Cet email est déjà utilisé'], Response::HTTP_CONFLICT);
        }
 
 
        // Création du nouvel utilisateur
        $user = new User();
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);
        $user->setAddress($data['address']);
        $user->setCp($data['cp']);
        $user->setTown($data['town']);
        $user->setCountry($data['country']);
        $user->setPhoneNumber($data['phoneNumber']);
 
 
        // encryptage du mot de passe
        $user->setPassword($userPasswordHasher->hashPassword($user, $data['password']));
        $entityManager->persist($user);
        $entityManager->flush();
 
        return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }
        
}
    

