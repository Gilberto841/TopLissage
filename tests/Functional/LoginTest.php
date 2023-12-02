<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testSomething(): void
    {
        /* creation d'un client */
        $client = static::createClient();

        /* genere la route */
        /** @var UrlgeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        /* navigation vers la route */
        $crawler = $client->request('GET', $urlGenerator->generate('app_login'));

       /*  $submitButton = $crawler->selectButton('Se Connecter');

        $form = $submitButton->form();
 */
    /* insertion des données dans le formulaire de connexion */
        $form = $crawler->filter("form[name=login]")->form([
            "email"=>"steph97tow@gmail.com",
            "password"=>"Hillions12?"
        ]);

        /* l'envoie des donnèes du formulaire */
        $client->submit($form); 

        /* la reponse du site par une redirection */
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        /* confirmation de la redirection */
        $client->followRedirect();
        
        /* route attendue par la redirection */
        $this->assertRouteSame('app_login');
    }
}
