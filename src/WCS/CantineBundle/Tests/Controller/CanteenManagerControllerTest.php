<?php

namespace WCS\CantineBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class CanteenManagerControllerCTest extends WebTestCase
{
    public function testCanteenManager()
    {
        $fixtures = array(
            'WCS\CantineBundle\DataFixtures\ORM\LoadUserData',
            'WCS\CantineBundle\DataFixtures\ORM\LoadSchoolData'
        );
        $this->fixtures = $this->loadFixtures($fixtures, null, 'doctrine', true)->getReferenceRepository();

        //test connexion dame cantine

        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Sonata\UserBundle\Controller\SecurityFOSUser1Controller::loginAction', $client->getRequest()->attributes->get('_controller'));

        $this->assertTrue($crawler->filter('form input[name="_username"]')->count() == 1);
        $this->assertTrue($crawler->filter('form input[name="_password"]')->count() == 1);

        $form = $crawler->selectButton('Connexion')->form();
        $form['_username'] = 'admin1@email.com';
        $form['_password'] = 'admin';

        $crawler = $client->submit($form);

        //test redirection vers page canteenManager avec les 3 boutons

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::indexAction', $client->getRequest()->attributes->get('_controller'));
    }

    /*public function testSchoolButton()
    {
        //test bouton "les écureuils"

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin1@email.com',
            'PHP_AUTH_PW' => 'admin',
        ));
        $crawler = $client->request('GET', '/canteenManager');
        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::todayListAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $link = $crawler
            ->filter('a#school')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);

        //suivre redirection vers page todaylist

        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::todayListAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }*/

   /* public function testLogout()
    {
        //test bouton 'logout'

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin1@email.com',
            'PHP_AUTH_PW' => 'admin',
        ));
        $crawler = $client->request('GET', '/canteenManager');
        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::indexAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $link = $crawler
            ->filter('a#logout')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);*/

        /*$this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //suivre redirection vers page login

        $this->assertEquals('Sonata\UserBundle\Controller\SecurityFOSUser1Controller::logoutAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }*/
}