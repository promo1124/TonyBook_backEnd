<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\SerializerInterface;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, SerializerInterface $serializer): Response
    {
        // return $this->render('produit/index.html.twig', [
        //     'produits' => $produitRepository->findAll(),
        // ]);
        $json = $serializer->serialize($produitRepository->findAll(), 'json', ['groups' => 'produits:read']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    #[Route('/new', name: 'app_produit_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupération des données de la requête
        $titre = $request->request->get('titre');
        $description = $request->request->get('description');
        $tarif = $request->request->get('tarif');

        // Création du produit
        $produit = new Produit();
        $produit->setTitre($titre);
        $produit->setDescription($description);
        $produit->setTarif($tarif);

        // Récupérer l'image depuis le formulaire (en utilisant VichUploader)
        /** @var UploadedFile $imageFile */
        $imageFile = $request->files->get('imageFile');
        if ($imageFile) {
            $produit->setImageFile($imageFile);
        }

        $entityManager->persist($produit);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Produit ajouté avec succès!'], Response::HTTP_CREATED);
    }


    #[Route('/{id}', name: 'app_produit_show', methods: ['GET', 'POST'])]
    public function show(Produit $produit, SerializerInterface $serializer): Response
    {
        // return $this->render('produit/show.html.twig', [
        //     'produit' => $produit,
        // ]);
        $json = $serializer->serialize($produit, 'json', ['groups' => 'produits:read']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['PUT'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        // $formData = json_decode($request->getContent());

        $entityManager->flush();

        // return $this->render('produit/edit.html.twig', [
        //     'produit' => $produit,
        //     'form' => $form,
        // ]);
        $json = $serializer->serialize($produit, 'json', ['groups' => 'produits:read']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['DELETE'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search/ok', name: 'app_produit_search', methods: ['GET'])]
    public function search(Request $request, ProduitRepository $produitRepository): JsonResponse {
        $criteria = $request->query->all(); 
        $produits = $produitRepository->findByCriteria($criteria);
    
        $data = array_map(function ($produit) {
            return [
                'id' => $produit->getId(),
                'titre' => $produit->getTitre(),               
                'description' => $produit->getDescription(),
                'photo' => $produit->getPhoto(),
                'tarif' => $produit->getTarif()
            ];
        }, $produits);

        return new JsonResponse($data);
    }
}
