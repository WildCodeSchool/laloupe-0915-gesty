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
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //test les champs à remplir sont présents






    }






}

