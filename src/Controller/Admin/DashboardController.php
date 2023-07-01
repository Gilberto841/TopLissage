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

    public function __construct( private AdminUrlGenerator $adminUrlGenerator){
        
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
        yield MenuItem::section('E-commerce','fa-solid fa-house');

        yield MenuItem::subMenu('Ajouter éléments','fa-solid fa-list')->setSubItems([
            
            MenuItem::linkToCrud('Nouveau Produit','fas fa-plus',Product::class)
            ->setAction(Crud::PAGE_NEW),

            MenuItem::linkToCrud('Nouvelle Categorie','fas fa-plus',Category::class)
            ->setAction(Crud::PAGE_NEW),

            MenuItem::linkToCrud('Nouveau Transporteur','fas fa-plus',Transporter::class)
            ->setAction(Crud::PAGE_NEW)
                    
        ]);
        yield MenuItem::subMenu('Afficher éléments','fa-solid fa-list')->setSubItems([

            MenuItem::linkToCrud('Les Produits', 'fa-solid fa-warehouse',Product::class),

            MenuItem::linkToCrud('LesCategorie',"fa-solid fa-newspaper",Category::class)
            
                    
        ]);
    }
}