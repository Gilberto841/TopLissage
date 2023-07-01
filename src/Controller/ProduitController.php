<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    #[Route('/mes-produits', name: 'produits')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        /* affiche les produits sur ma page /mes-produits */
        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();

        return $this->render('produit/index.html.twig', [
            'products' => $products
        ]);
    }
}
