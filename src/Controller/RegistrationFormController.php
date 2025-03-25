<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use RegistrationFormType as GlobalRegistrationFormType;
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
 
        /**
         * ON N'utilise plus de formulaire symfony
         * les données sont envoyées par REACT
         */
       
        /*$form = $this->createForm(GlobalRegistrationFormType::class, $user);
            // $form->handleRequest($request);
            $form->submit($data);
 
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var string $plainPassword *
                $plainPassword = $form->get('plainPassword')->getData();
 
               
               
            return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_CREATED);
        }*/
 
        // encryptage du mot de passe
        $user->setPassword($userPasswordHasher->hashPassword($user, $data['password']));
        $entityManager->persist($user);
        $entityManager->flush();
 
        return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }
        
}
    

