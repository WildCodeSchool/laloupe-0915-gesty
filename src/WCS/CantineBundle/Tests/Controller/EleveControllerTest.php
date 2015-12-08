<?php

namespace WCS\CantineBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EleveControllerTest extends WebTestCase
{
    public function testPageInscription()
    {
        //création client fictif

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa',
            'PHP_AUTH_PW' => 'aaa',
        ));

        //test si page inscription enfant s'affiche

        $crawler = $client->request('GET', '/eleve/create');
        $this->assertEquals('WCS\CantineBundle\Controller\EleveController::createAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        //test bouton inscrire mon enfant quand on est connecté (formulaire)


    }


    public function testConnexion()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');


        //Create a new entry in the database
        $crawler = $client->request('GET', '/eleve/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /eleve/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        $this->assertTrue($crawler->filter('form input[name="_username"]')->count() == 1);

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
}
