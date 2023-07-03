<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Product;
use App\Service\CartService;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{

    private EntityManagerInterface $em;
    private UrlGeneratorInterface $generatorUrl;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $generatorUrl)
    {
        $this->em = $em;
        $this->generatorUrl = $generatorUrl;
    }

    // Route pour créer une session de paiement Stripe
    #[Route('/order/create-session-stripe/{reference}', name: 'payment_stripe')]
    public function stripeCheckout($reference): RedirectResponse
    {
        // Variable pour stocker les produits à envoyer à Stripe
        $productStripe = [];

        // Récupère la commande correspondant à la référence
        $order = $this->em->getRepository(Order::class)->findOneBy([
            'reference' => $reference
        ]);

        if (!$order) {
            return $this->redirectToRoute('cart_index');
        }

        // Récupère les produits de la commande et les ajoute à $productStripe
        foreach ($order->getRecapDetails()->getValues() as $product) {
            // Récupère le produit par son nom
            $productData = $this->em->getRepository(Product::class)->findOneBy(['name' => $product->getProduct()]);
            
            $productStripe[] = [
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => intval($productData->getPrice() * 100), // Le montant doit être en centimes
                    'product_data' => [
                        'name' => $product->getProduct()
                    ]
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        // Ajoute le transporteur à $productStripe
        $productStripe[] = [
            'price_data' => [
                'currency' => 'EUR',
                'unit_amount' => $order->getTransporteurPrice(),
                'product_data' => [
                    'name' => $order->getTransporterName()
                ]
            ],
            'quantity' => 1,
        ];

        // Récupère l'email de l'utilisateur connecté
        $emailUser = $order->getProfessional()->getEmail();

        // Initialise Stripe avec votre clé secrète
        Stripe::setApiKey('YOUR_STRIPE_SECRET_KEY');

        // Crée une session de paiement avec les détails du produit
        $checkout_session = Session::create([
            'customer_email' => $emailUser,
            'payment_method_types' => ['card'],
            'line_items' => [$productStripe],
            'mode' => 'payment',
            'success_url' => $this->generatorUrl->generate('payment_success', [
                'reference' => $order->getReference(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generatorUrl->generate('payment_cancel', [
                'reference' => $order->getReference(),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'automatic_tax' => [
                'enabled' => true,
            ],
        ]);

        // Stocke l'ID de la session Stripe dans l'entité Order
        $order->setStripeSessionId($checkout_session->id);
        $this->em->flush();

        // Redirige l'utilisateur vers la page de paiement Stripe
        return new RedirectResponse($checkout_session->url);
    }

    // Route pour le succès de paiement
    #[Route('/order/success/{reference}', name: 'payment_success')]
    public function stripeSuccess($reference, CartService $cartService): Response
    {
        return $this->render('order/success.html.twig', [
            'reference' => $reference
        ]);
    }

    // Route pour l'annulation de paiement
    #[Route('/order/error/{reference}', name: 'payment_cancel')]
    public function stripeCancel($reference, CartService $cartService): Response
    {
        return $this->render('order/cancel.html.twig', [
            'reference' => $reference
        ]);
    }
}
