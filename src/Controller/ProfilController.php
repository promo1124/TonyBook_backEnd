<?php
namespace App\Controller;

use App\Form\ProfilUpdateType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/api/profil/{id}', name: 'api_profil', methods: ['GET','POST'])]
    public function getProfil(User $user): JsonResponse
    {
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
        ]);
    }
    #[Route('/profil', name: 'app_profil')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Utilisation de la méthode getUser() directement
        $user = $this->getUser();

        // Vérifier que l'utilisateur est bien authentifié
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Créer le formulaire avec les données de l'utilisateur connecté
        $form = $this->createForm(ProfilUpdateType::class, $user);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le mot de passe a été changé
            $plainPassword = $form->get('plainPassword')->getData();
            
            if ($plainPassword) {
                // Vérification que le mot de passe n'est pas vide
                if (strlen($plainPassword) >= 6) {
                    $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
                    $user->setPassword($hashedPassword); // Mise à jour du mot de passe
                } else {
                    // Ajouter un message d'erreur si le mot de passe est trop court
                    $this->addFlash('error', 'Le mot de passe doit contenir au moins 6 caractères.');
                    return $this->redirectToRoute('app_profil');
                }
            }

            // Sauvegarde les modifications dans la base de données
            $entityManager->flush();

            // Message flash de succès
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès!');

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
