<?php

namespace WCS\CantineBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class CanteenManagerControllerCTest extends WebTestCase
{
    public function testCanteenManager()
    {
        $fixtures = array(
            'WCS\CantineBundle\DataFixtures\ORM\LoadSchoolData'
        );
        $this->fixtures = $this->loadFixtures($fixtures, null, 'doctrine', true)->getReferenceRepository();

        /*//test création dame de cantine fictive

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin1@email.com',
            'PHP_PW'=> 'admin',
        ));*/

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

        /*//test redirection vers page canteenManager avec les 3 boutons

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::indexAction', $client->getRequest()->attributes->get('_controller'));
        */

    }
}