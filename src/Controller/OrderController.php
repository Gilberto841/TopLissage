<?php

namespace App\Controller;

use DateTime;
use App\Entity\Order;
use DateTimeImmutable;
use App\Form\OrderType;
use App\Entity\RecapDetail;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/order/create', name: 'order')]
    public function index(CartService $cartService): Response
    {

        if (!$this->getUser()) {

            return $this->redirectToRoute('app_login');
        }

        /* ajout des information de l'utilisateur connecté pour passer la commande */
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'recapCart' => $cartService->getTotal()

        ]);
    }

    #[Route('/order/verify', name: 'order_prepare', methods: ['POST'])]
    public function prepareOrder(Request $request, CartService $cartService): Response
    {

        if (!$this->getUser()) {

            
            return $this->redirectToRoute('app_login');
        }
        
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        /* on recupere les données de la commande */

        $form->handleRequest($request);

        

        /* on controle si le formulaire a été soumis et si le formulaire est validé  */
        if ($form->isSubmitted() && $form->isValid()) {
            $datetime = new DateTimeImmutable('now');
           
            /* on recupere les données de transporter par la clé get('transporter') */
            $transporter = $form->get('transporter')->getData();
            /* on initialise le delivery */
            $delivery = $form->get('addresses')->getData();
            $deliveryForOrder = $delivery->getFirstName() . '' . $delivery->getLastName();
            $deliveryForOrder .= '<\br>' . $delivery->getPhone();
            if ($delivery->getCompany()) {
                $deliveryForOrder .= '-' . $delivery->getCompany();
            }
            $deliveryForOrder .= '<\br>' . $delivery->getAddress();
            $deliveryForOrder .= '<\br>' . $delivery->getPostalCode() . '-' . $delivery->getCity();
            $deliveryForOrder .= '<\br>' . $delivery->getCountry();

            /* on initialise notre entity order */
            $order = new Order();
            $reference = $datetime->format('Ymd') . '-' . uniqid();
            $order->setProfessional($this->getUser());
            ($this->getUser());
            $order->setReference($reference);
            $order->setCreatedAt($datetime);
            $order->setDelivery($deliveryForOrder);
            $order->setTransporterName($transporter->getTitle());
            $order->setTransporteurPrice($transporter->getPrice());
            $order->setIsPaid(0);
            $order->setMethod('stipe');

            /* ajout des données de la commande dans la variable $order */
            $this->em->persist($order);
           


            /* affiche tous les éléments du getTotal() */
            foreach ($cartService->getTotal() as $product) {
                /* ajout dans le RecapDetails */

                $recapDetails = new RecapDetail();
                $recapDetails->setOrderProduct($order);
                $recapDetails->setQuantity($product['quantity']);
                $recapDetails->setPrice($product['product']->getPrice());
                $recapDetails->setProduct($product['product']->getName());
                $recapDetails->setTotalRecap($product['product']->getPrice()*$product['quantity']);

                
            /* ajout des données de la commande dans la variable $recapDetails */
            $this->em->persist($recapDetails);
            }

            /* Injection des Informations receuillis dans la base de Données */
            $this->em->flush();

           /* retourne vers la page si le formulaire a bien été soumis */ 
           return $this->render('order/recap.html.twig',[
                'method' => $order->getMethod(),
                'recapCart' => $cartService->getTotal(),
                'transporter' => $transporter,
                'delivery' => $deliveryForOrder,
                "reference" => $order->getReference()
           ]);
        }
        
        return $this->redirectToRoute('cart_index');
    }
}