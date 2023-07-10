<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // Route pour afficher la page d'accueil
    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    // Route pour la page d'accueil par défaut (route '/')
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // Redirige vers la route 'home' pour afficher la page d'accueil
        return $this->redirectToRoute('home');
    }
}
