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
    #[Route('/registration/form', name: 'app_registration_form')]
    public function index(): Response
    {
        return $this->render('registration_form/index.html.twig', [
            'controller_name' => 'RegistrationFormController',
        ]);
    }

    #[Route('/register', name: 'app_register', methods: ['POST'])]

    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(GlobalRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encryptage du mot de passe
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_CREATED);
}
return new JsonResponse(['message' => 'User not registered'], Response::HTTP_BAD_REQUEST);
}
}
