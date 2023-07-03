<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Transporter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Redirection vers la page de liste des produits (ProductCrudController)
        $url = $this->adminUrlGenerator
            ->setController(ProductCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    // Configuration générale du tableau de bord
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TopLissage');
    }

    // Configuration du menu latéral
    public function configureMenuItems(): iterable
    {
        // Section E-commerce avec une icône
        yield MenuItem::section('E-commerce', 'fa-solid fa-house');

        // Sous-menu pour ajouter des éléments
        yield MenuItem::subMenu('Ajouter éléments', 'fa-solid fa-list')->setSubItems([
            MenuItem::linkToCrud('Nouveau Produit', 'fas fa-plus', Product::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Nouvelle Categorie', 'fas fa-plus', Category::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Nouveau Transporteur', 'fas fa-plus', Transporter::class)
                ->setAction(Crud::PAGE_NEW)
        ]);

        // Sous-menu pour afficher des éléments
        yield MenuItem::subMenu('Afficher éléments', 'fa-solid fa-list')->setSubItems([
            MenuItem::linkToCrud('Les Produits', 'fa-solid fa-warehouse', Product::class),
            MenuItem::linkToCrud('LesCategorie', 'fa-solid fa-newspaper', Category::class)
        ]);
    }
}
