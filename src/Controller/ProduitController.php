<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    // Route pour afficher la liste des produits
    #[Route('/mes-produits', name: 'produits')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupère le référentiel des produits
        $productRepository = $entityManager->getRepository(Product::class);
        
        // Récupère tous les produits
        $products = $productRepository->findAll();

        // Affiche la liste des produits en utilisant un fichier de modèle Twig
        return $this->render('produit/index.html.twig', [
            'products' => $products
        ]);
    }
}
