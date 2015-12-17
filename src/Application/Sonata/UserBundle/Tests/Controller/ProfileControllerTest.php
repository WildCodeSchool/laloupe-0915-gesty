<?php

namespace src\Application\Sonata\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//lien http://brentertainment.com/other/docs/book/testing.html

Class ProfileControllerTest extends WebTestCase
{
    //test que les champs à remplir existent

    public function testFields()
    {
        // création client fictif

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa@email.com',
            'PHP_AUTH_PW' => 'aaa',
        ));

        //test la page "profile" s'affiche

        $crawler = $client->request('GET', '/profile/');
        $this->assertEquals('Application\Sonata\UserBundle\Controller\ProfileController::setProfileAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        //test les champs à remplir sont présents

        $crawler = $client->request('GET', '/profile/');

        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_lastname')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_firstname')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_adresse')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_codePostal')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_phone')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_telephoneSecondaire')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_caf')->count());
        $this->assertEquals(1, $crawler->filter('form input#application_sonata_user_profile_numeroIban')->count());



        $form['application_sonata_user_profile[lastname]'] = 'Aaa';
        $form['application_sonata_user_profile[firstname]'] = 'Aaa';
        $form['application_sonata_user_profile[adresse]'] = '4 rue du bois';
        $form['application_sonata_user_profile[codePostal]'] = '28240';
        $form['application_sonata_user_profile[phone]'] = '0768298272';
        $form['application_sonata_user_profile[telephoneSecondaire]']='0768298272';
        $form['application_sonata_user_profile[caf]']='1234567';
        $form['application_sonata_user_profile[numeroIban]']='1234567891011121314151617181920AZERTYU';

        $form = $crawler->selectButton('Envoyer')->form();

        $crawler = $client->submit($form);



        //test bouton 'Envoyer'

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'aaa@email.com',
            'PHP_AUTH_PW' => 'aaa',
        ));
        $crawler = $client->request('GET', '/profile/');
        $this->assertEquals('Application\Sonata\UserBundle\Controller\ProfileController::setProfileAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $link = $crawler
            ->filter('button#application_sonata_user_profile_envoyer')
            ->eq(0)
            ->link();
        $crawler = $client->click($link);







    }






}

