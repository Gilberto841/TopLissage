<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    public function __construct( private AdminUrlGenerator $adminUrlGenerator) 
    {
        
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

       $url = $this->adminUrlGenerator
            ->setController(ProductCrudController::class)
            ->generateUrl();

        return $this->redirect($url);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TopLissage');
    }

    public function configureMenuItems(): iterable
    {
        /* Création d'un champs permettant d'aller au Dashboard */
        yield MenuItem::linkToDashboard('Accueil', 'fa-solid fa-globe');
        /* Création d'une section */
        yield MenuItem::section('E-commerce','fa-solid fa-house');
        /* création de menu contenant de sous menus */
        yield MenuItem::subMenu('Ajouter éléments','fa-solid fa-list')->setSubItems([
            /* sous menu permettant à la page de création des produits */
            MenuItem::linkToCrud('Nouveau Produit','fas fa-plus',Product::class)
            ->setAction(Crud::PAGE_NEW),
            /*  sous menu permettant à la page de création des catégories  */
            MenuItem::linkToCrud('Nouvelle Categorie',"fa-salid fa-newspaper",Category::class)
            ->setAction(Crud::PAGE_NEW)
        ]);
          /* c'est un menu qui contient des sous menus */  
        yield MenuItem::subMenu('Afficher éléments','fa-solid fa-list')->setSubItems([
             /* c'est un sous menu qui permet d'afficher les produits */   
            MenuItem::linkToCrud('Afficher Produit','fas fa-plus',Product::class),
            /*  c'est un sous menu qui permet d'afficher les catégories */
            MenuItem::linkToCrud('Afficher Categorie',"fa-salid fa-newspaper",Category::class)
        ]);
    }
}
