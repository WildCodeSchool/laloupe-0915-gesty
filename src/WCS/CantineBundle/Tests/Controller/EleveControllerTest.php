<?php

namespace WCS\CantineBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EleveControllerTest extends WebTestCase
{


    public function testConnexion()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertTrue($crawler->filter('form input[name="_username"]')->count() == 1);
        $this->assertTrue($crawler->filter('form input[name="_password"]')->count() == 1);

        //test la connexion quand j'ai déjà un compte


        $form = $crawler->selectButton('Connexion')->form();
        $form['_username'] = 'aaa';
        $form['_password'] = 'aaa';

        $crawler = $client->submit($form);


        //suivre la redirection

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals('Sonata\UserBundle\Controller\SecurityFOSUser1Controller::loginAction', $client->getRequest()->attributes->get('_controller'));

    }

    public function testPageInscription()
    {
        //création client fictif

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW' => 'aaa',
        ));

        //test si page inscription enfant s'affiche

        $crawler = $client->request('GET', '/create');
        $this->assertEquals('WCS\CantineBundle\Controller\EleveController::createAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        //test bouton inscrire mon enfant quand on est connecté (formulaire)


    }

    //test connexion quand je créée un compte

    public function testRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertEquals(1, $crawler->filter('form input#sonata_user_registration_form_email')->count());
        $this->assertEquals(1, $crawler->filter('form input#sonata_user_registration_form_plainPassword_first')->count());
        $this->assertEquals(1, $crawler->filter('form input#sonata_user_registration_form_plainPassword_second')->count());

        $form = $crawler->selectButton('Enregistrer')->form();
        $form['sonata_user_registration_form[email]'] = 'bbb';
        $form['sonata_user_registration_form[plainPassword][first]'] = 'bbb';
        $form['sonata_user_registration_form[plainPassword][second]'] = 'bbb';

        //suivre la redirection

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Sonata\UserBundle\Controller\SecurityFOSUser1Controller::loginAction', $client->getRequest()->attributes->get('_controller'));

    }

    //test page après connexion compte (bienvenue /) et bouton

    public function testBienvenu()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'mistert@mistert.fr',
            'PHP_AUTH_PW' => 'mistert',
        ));
        $crawler = $client->request('GET', '/');

        //test affichage page bienvenue

        $this->assertEquals('WCS\GestyBundle\Controller\DashboardController::indexAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(302,$client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('Sonata\UserBundle\Controller\SecurityFOSUser1Controller::loginAction', $client->getRequest()->attributes->get('_controller'));

        /*//test bouton 'voir les détails'
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'mistert@mistert.fr',
            'PHP_AUTH_PW' => 'mistert',
        ));
        $crawler = $client->request('GET', '/');
        $link = $crawler
            ->filter('a:contains("Voir les details")')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);

        //suivre redirection vers page dashboard

        $this->assertEquals('WCS\CantineBundle\Controller\EleveController::dashboardAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

       */
    }

    public function testDashboard()
    {
        //test bouton 'logout'
       $client = static::createClient(array(), array(
           'PHP_AUTH_USER' => 'mistert@mistert.fr',
           'PHP_AUTH_PW' => 'mistert',
       ));
       $crawler = $client->request('GET', '/');
       $link = $crawler
           ->filter('a:contains("logout")')
           ->eq(0)
           ->link();
       $crawler = $client->click($link);

       //suivre redirection vers page dashboard

       $this->assertEquals('WCS\CantineBundle\Controller\EleveController::dashboardAction', $client->getRequest()->attributes->get('_controller'));
       $this->assertTrue(200 === $client->getResponse()->getStatusCode());




    }
}
