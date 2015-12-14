<?php

namespace WCS\CantineBundle\Tests\Controller;


use Liip\FunctionalTestBundle\Test\WebTestCase;

class EleveControllerTest extends WebTestCase
{


    public function testConnexion()
    {
        $fixtures = array(
            'WCS\CantineBundle\DataFixtures\ORM\LoadUserData'
        );
        $this->fixtures = $this->loadFixtures($fixtures, null, 'doctrine', true)->getReferenceRepository();

        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Sonata\UserBundle\Controller\SecurityFOSUser1Controller::loginAction', $client->getRequest()->attributes->get('_controller'));

        $this->assertTrue($crawler->filter('form input[name="_username"]')->count() == 1);
        $this->assertTrue($crawler->filter('form input[name="_password"]')->count() == 1);

        $form = $crawler->selectButton('Connexion')->form();
        $form['_username'] = 'aaa@email.com';
        $form['_password'] = 'aaa';

        $crawler = $client->submit($form);


        //suivre la redirection

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('WCS\GestyBundle\Controller\DashboardController::indexAction', $client->getRequest()->attributes->get('_controller'));

    }

    public function testPageInscription()
    {
        //création client fictif

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa@email.com',
            'PHP_AUTH_PW' => 'aaa',
        ));

        //test si page inscription enfant s'affiche

        $crawler = $client->request('GET', '/create');
        $this->assertEquals('WCS\CantineBundle\Controller\EleveController::createAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

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
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        //test affichage page bienvenue

        $this->assertEquals('WCS\GestyBundle\Controller\DashboardController::indexAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(302,$client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals('Sonata\UserBundle\Controller\SecurityFOSUser1Controller::loginAction', $client->getRequest()->attributes->get('_controller'));

        //test bouton 'voir les détails'

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa@email.com',
            'PHP_AUTH_PW' => 'aaa',
        ));
        $crawler = $client->request('GET', '/');
        $link = $crawler
            ->filter('a:contains("Voir les détails")')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);

        //suivre redirection vers page dashboard

        $this->assertEquals('WCS\CantineBundle\Controller\EleveController::dashboardAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());


    }

    public function testDashboard()
    {
        //test bouton 'logout'
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa@email.com',
            'PHP_AUTH_PW' => 'aaa',
        ));
        $crawler = $client->request('GET', '/');
        $this->assertEquals('WCS\GestyBundle\Controller\DashboardController::indexAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $link = $crawler
            ->filter('a#logout')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);

        //suivre redirection vers page dashboard

        $this->assertEquals('Sonata\UserBundle\Controller\SecurityFOSUser1Controller::logoutAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

    }
}
