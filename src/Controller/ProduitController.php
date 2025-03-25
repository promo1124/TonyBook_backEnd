<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Serializer\SerializerInterface;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, SerializerInterface $serializer): Response
    {
        // return $this->render('produit/index.html.twig', [
        //     'produits' => $produitRepository->findAll(),
        // ]);
        $json = $serializer->serialize($produitRepository->findAll(), 'json', ['groups' => 'produits:read']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $produit = new Produit();

        $formData = json_decode($request->getContent());

        $produit->setTitre($formData['titre']);
        $produit->setDescription($formData['description']);
        $produit->setPhoto($formData['photo']);
        $produit->setTarif($formData['tarif']);

        // return $this->render('produit/new.html.twig', [
        //     'produit' => $produit,
        //     'form' => $form,
        // ]);

        $entityManager->persist($produit);
        $entityManager->flush();

        return new JsonResponse(['message' => 'votre produit a bien été ajouté'], Response::HTTP_OK, [], true);
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
}
