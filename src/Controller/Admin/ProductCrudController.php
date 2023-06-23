<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController 
{
    public const PRODUCTS_PATH_BASE = 'upload/images/products';
    public const PRODUCTS_UPLOAD_DIR = 'public/upload/images/products';
    public const ACTION_DUPLICATE = 'duplicate';

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }
    /* Création d'un bouton DUPLICATE qui se place sur la page Edit d'un élément */
    public function configureActions(Actions $actions): Actions
    {
        /* création d'un bouton pour dupliquer */
        $duplicate = Action::new(self::ACTION_DUPLICATE)
                            ->linkToCrudAction('duplicateProduct')
                            /* ajout d'un style bootstrp */
                            ->setCssClass('btn btn-info');
        return $actions
        /* on place le bouton dupliquer sur la page edit (modifier) */
        ->add(Crud::PAGE_EDIT,$duplicate);
    }
    /* fonction permettant à l'action de dupliquer un élément selectionné, via le bouton duplicate */
    public function duplicateProduct(AdminContext $context, /* Variable $context contenant les informations des produits selectionnés */
                                    EntityManagerInterface $entityManager,/* Variable et class appartenant persistEntity */
                                    AdminUrlGenerator $adminUrlGenerator/* On génére un URL */
                                    ):Response

    {
        /* déclaration de la variable sur la class Product */
        /** @var Product $product  */
        /* Variable product contenant l'Instance la variable context */
        $product = $context->getEntity()->getInstance();
        /* variable permettant de cloner le produit */
        $duplicateProduct = clone $product;
        /* appel la public fonction persistEntity dans l'asbtractCrudController  */
        parent::persistEntity($entityManager, $duplicateProduct);
    /* url pour afficher page détail après avoir dupliqué */
        $url = $adminUrlGenerator->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($duplicateProduct->getId())
            ->generateUrl();
        
        /* URL permettant d'aller à la plage détail après avoir dupliquer */
         return $this->redirect($url);   

    }
    /* fonction public permettant de gérer les champs vu dans le formulaire de création d'un produit */
    public function configureFields(string $pageName): iterable

    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            AssociationField::new('category', 'Catégorie')->setFormTypeOptions([
                'class' => Category::class,
                'choice_label' => 'name',
            ]),
            TextEditorField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
            NumberField::new('quantity')->setNumDecimals(0),
            ImageField::new('image')->setBasePath(self::PRODUCTS_PATH_BASE)->setUploadDir(self::PRODUCTS_UPLOAD_DIR)->setSortable(false),
            DateTimeField::new('updatedAt')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),





        ];
    }
    /* function permettant de créer la date CreatedAt à l'action de la création d'un produit */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /* Controle si la variable $entityInstance est une instance de la classe Catégory */
        if (!$entityInstance instanceof Product ) {
            
            return;
        }
        /* si elle n'existe pas on crée le champ CreatedAt avec le type DateTimeImmutalbe  */
        $entityInstance->setCreatedAt(new \DateTimeImmutable);

        /* Utiliser la fonctionnalité présente dans l'AbstractController */
        parent::persistEntity($entityManager, $entityInstance);

        
    }

/* function permettant de créer la date UpdatedAt à l'action de la mise a jour d'un produit */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
{
    if (!$entityInstance instanceof Product ) {
            
        return;
}
   /* si elle n'existe pas on crée le champ CreatedAt avec le type DateTimeImmutalbe  */
   $entityInstance->setUpdatedAt(new \DateTimeImmutable);
   
   /* Utiliser la fonctionnalité présente dans l'AbstractController */
   parent::updateEntity($entityManager, $entityInstance);
}
   
}
