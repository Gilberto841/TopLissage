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
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, 
                        EntityManagerInterface $em,
                        MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);      

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $data = $form->getData();

            $data->setCreatedAt(new DateTimeImmutable()); 

                       
            $address = $form->get('email')->getData();
            $content = $form->get('message')->getData();
            $objet = $form->get('objet')->getData();
            
            $email = (new Email())
                    ->from($address)
                    ->to('padrhino@outlook.fr')
                    ->subject($objet)
                    ->text($content);
            
            $mailer->send($email);

            $em->persist($data);

            $em->flush();

            $this->addFlash(
                'success',
                'votre post à bien été envoyé !'
            );

            return $this->redirectToRoute('app_contact');

            
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
