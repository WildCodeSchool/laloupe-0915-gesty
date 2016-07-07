<?php

namespace WCS\CantineBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


//lien http://brentertainment.com/other/docs/book/testing.html

Class ProfileController____ extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;

    /**
     * Authentification avant chaque test
     */
    protected function setUp()
    {
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa@email.com',
            'PHP_AUTH_PW' => 'aaa',
        ));
    }


    /**
     * S'assure que les routes sont correctes
     */
    public function testRoutes()
    {
        $crawler = $this->client->request('GET', '/profil');
        $this->assertNotNull($crawler);

        $this->assertEquals(
            'WCS\CantineBundle\Controller\ProfileController::editAction',
            $this->client->getRequest()->attributes->get('_controller'),
            'Controlleur correct attendu'
        );

        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode(), 'Status HTTP 200 attendu');
    }


    /**
     * test que les champs à remplir existent
     */
    public function testFieldsExist()
    {
        $crawler = $this->client->request('GET', '/profil');
        $this->assertNotNull($crawler);

        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_lastname')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_firstname')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_adresse')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_codePostal')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_phone')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_telephoneSecondaire')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_caf')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_numeroIban')->count());
    }

    /**
     * test qu'appuyer sur le bouton submit ne change rien
     */
    public function testSubmitWithoutChange()
    {
        $crawler = $this->client->request('GET', '/profil/');
        $this->assertNotNull($crawler);

        $form = $crawler->selectButton('Envoyer')->form();
        $crawler = $this->client->submit($form);

        $this->assertNotNull($crawler);
    }

    /**
     * test que les modifications d'un profil fonctionne
     */
    public function testEditProfile()
    {
        $crawler = $this->client->request('GET', '/profil');

        $form = $crawler->selectButton('Envoyer')->form();

        $form['application_sonata_user_profile[lastname]']              = 'Aaa';
        $form['application_sonata_user_profile[firstname]']             = 'Aaa';
        $form['application_sonata_user_profile[adresse]']               = '4 rue du bois';
        $form['application_sonata_user_profile[codePostal]']            = '28240';
        $form['application_sonata_user_profile[phone]']                 = '0768298272';
        $form['application_sonata_user_profile[telephoneSecondaire]']   = '0768298272';
        $form['application_sonata_user_profile[caf]']                   = '1234567';
        $form['application_sonata_user_profile[numeroIban]']            = '1234567891011121314151617181920AZERTYU';

        $crawler = $this->client->submit($form);
        $this->assertNotNull($crawler);
    }

    //test que l'on peut sortir de cette page

    public function testOut()
    {
        $this->client->request('GET', '/');
        $crawler = $this->client->followRedirect();

        $link = $crawler
            ->filter('a#logout')
            ->eq(0)
            ->link();
        $this->client->click($link);

        //suivre redirection vers page login quand click sur 'logout'

        $this->assertEquals(
            'Sonata\UserBundle\Controller\SecurityFOSUser1Controller::logoutAction',
            $this->client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    }
}
