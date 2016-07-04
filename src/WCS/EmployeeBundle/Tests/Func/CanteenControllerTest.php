<?php
/*
namespace WCS\EmployeeBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class EmployeeControllerTest extends WebTestCase
{
    public function testEmployeeManager()
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
        $form['_username'] = 'damedecantine@email.com';
        $form['_password'] = 'aaa';

        $client->submit($form);

        //test redirection vers page canteenManager avec les 3 boutons

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('WCS\EmployeeBundle\Controller\HomeController::indexAction', $client->getRequest()->attributes->get('_controller'));

    }

    public function testSchoolButton()
    {
        //test bouton "les écureuils"

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'damedecantine@email.com',
            'PHP_AUTH_PW' => 'aaa',
        ));

        $crawler = $client->request('GET', '/manager/');
        $this->assertEquals('WCS\EmployeeBundle\Controller\HomeController::indexAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler
            ->filter('a:contains("Les écureuils")')
            ->eq(0)
            ->link();
        $client->click($link);

            //suivre redirection vers page todaylist

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('WCS\EmployeeBundle\Controller\HomeController::showSchoolsAction', $client->getRequest()->attributes->get('_controller'));

        //test bouton "Notre Dame des Fleurs"

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'damedecantine@email.com',
            'PHP_AUTH_PW' => 'aaa',
        ));
        $crawler = $client->request('GET', '/manager/');
        $this->assertEquals('WCS\EmployeeBundle\Controller\CanteenManagerController::indexAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler
            ->filter('a:contains("Notre Dame des Fleurs")')
            ->eq(0)
            ->link();
        $client->click($link);

            //suivre redirection vers page todaylist

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::todayListAction', $client->getRequest()->attributes->get('_controller'));

        //test bouton "Roland-Garros"

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'damedecantine@email.com',
            'PHP_AUTH_PW' => 'aaa',
        ));
        $crawler = $client->request('GET', '/canteenManager/');
        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::indexAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler
            ->filter('a:contains("Roland-Garros")')
            ->eq(0)
            ->link();
        $client->click($link);

            //suivre redirection vers page todaylist

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::todayListAction', $client->getRequest()->attributes->get('_controller'));

        //test bouton "Commande des repas"

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'damedecantine@email.com',
            'PHP_AUTH_PW' => 'aaa',
        ));
        $crawler = $client->request('GET', '/canteenManager/');
        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::indexAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler
            ->filter('a:contains("Commande des repas")')
            ->eq(0)
            ->link();
        $client->click($link);

            //suivre redirection vers page reservation

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::commandeAction', $client->getRequest()->attributes->get('_controller'));


    }

    public function testLogout()
    {

        $fixtures = array(
            'WCS\CantineBundle\DataFixtures\ORM\LoadUserData',
            'WCS\CantineBundle\DataFixtures\ORM\LoadSchoolData'
        );
        $this->fixtures = $this->loadFixtures($fixtures, null, 'doctrine', true)->getReferenceRepository();

        //test bouton 'logout'

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'damedecantine@email.com',
            'PHP_AUTH_PW' => 'aaa',));

        $crawler = $client->request('GET', '/canteenManager/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('WCS\CantineBundle\Controller\CanteenManagerController::indexAction', $client->getRequest()->attributes->get('_controller'));

        $link = $crawler
            ->filter('a#logout')
            ->eq(0)
            ->link();
        $client->click($link);

        $this->assertEquals('Sonata\UserBundle\Controller\SecurityFOSUser1Controller::logoutAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

    }
}
*/
