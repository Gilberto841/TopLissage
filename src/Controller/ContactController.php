<?php

namespace App\Controller;

use App\Form\ContactType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    // Route pour afficher le formulaire de contact
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, 
                        EntityManagerInterface $em,
                        MailerInterface $mailer): Response
    {
        // Crée une instance du formulaire ContactType
        $form = $this->createForm(ContactType::class);

        // Gère la requête du formulaire
        $form->handleRequest($request);      

        // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) 
        {
            // Récupère les données soumises par le formulaire
            $data = $form->getData();

            // Définit la date de création du message en utilisant DateTimeImmutable
            $data->setCreatedAt(new DateTimeImmutable()); 

            // Récupère l'adresse e-mail, le contenu et l'objet du message à partir du formulaire
            $address = $form->get('email')->getData();
            $content = $form->get('message')->getData();
            $objet = $form->get('objet')->getData();
            
            // Crée une instance de l'e-mail à envoyer
            $email = (new Email())
                    ->from($address)
                    ->to('padrhino@outlook.fr')
                    ->subject($objet)
                    ->text($content);
            
            // Envoie l'e-mail en utilisant le service MailerInterface
            $mailer->send($email);

            // Persiste les données du formulaire dans la base de données
            $em->persist($data);
            $em->flush();

            // Ajoute un message flash pour indiquer que le message a été envoyé avec succès
            $this->addFlash(
                'success',
                'Votre message a bien été envoyé !'
            );

            // Redirige vers la route 'app_contact'
            return $this->redirectToRoute('app_contact');
        }

        // Rend la vue 'contact/index.html.twig' en passant le formulaire
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
