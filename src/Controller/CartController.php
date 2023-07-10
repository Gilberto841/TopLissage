<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CartController extends AbstractController
{
    // Route pour afficher le contenu du panier
    #[Route('/mon-panier', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        try {
            $this->denyAccessUnlessGranted("ROLE_PROFESSIONAL");
        } catch (AccessDeniedException $exception) {
            $this->addFlash('danger', "Cette partie du site est réservée veuillez vous connecté");
            return $this->redirectToRoute('app_login');
        }
        // Rend la vue 'cart/index.html.twig' en passant le contenu du panier
        return $this->render('cart/index.html.twig', [
           'cart' => $cartService->getTotal()
        ]);
    }

    // Route pour ajouter un produit au panier
    #[Route('/mon-panier/add/{id<\d+>}', name: 'cart_add')]
    public function addToCart(CartService $cartService, int $id): Response
    {
        // Appelle la méthode addToCart du service CartService pour ajouter le produit au panier
        $cartService->addToCart($id);

        // Redirige vers la route 'cart_index' pour afficher le contenu du panier
        return $this->redirectToRoute('cart_index');
    }

    // Route pour supprimer un produit du panier
    #[Route('/mon-panier/remove/{id<\d+>}', name: 'cart_remove')]
    public function removeToCart(CartService $cartService, int $id): Response
    {
        // Appelle la méthode removeToCart du service CartService pour supprimer le produit du panier
        $cartService->removeToCart($id);

        // Redirige vers la route 'cart_index' pour afficher le contenu du panier
        return $this->redirectToRoute('cart_index');
    }

    // Route pour diminuer la quantité d'un produit dans le panier
    #[Route('/mon-panier/decrease/{id<\d+>}', name: 'cart_decrease')]
    public function decrease(CartService $cartService, $id): RedirectResponse
    {
        // Appelle la méthode decrease du service CartService pour diminuer la quantité du produit dans le panier
        $cartService->decrease($id);

        // Redirige vers la route 'cart_index' pour afficher le contenu du panier
        return $this->redirectToRoute('cart_index');
    }

    // Route pour supprimer tous les produits du panier
    #[Route('/mon-panier/removeAll', name: 'cart_removeAll')]
    public function removeAll(CartService $cartService): Response
    {
        // Appelle la méthode revoveCartAll du service CartService pour supprimer tous les produits du panier
        $cartService->removeCartAll();

        // Redirige vers la route 'index'
        return $this->redirectToRoute('index');
    }
}
