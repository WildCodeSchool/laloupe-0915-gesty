<?php

namespace Gesty\GestyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gesty\GestyBundle\Entity\User;
use Gesty\GestyBundle\Form\Type\MessageType;

class DefaultController extends Controller
{
    public function registerAction()
    {

            $sendMessage = \Swift_Message::newInstance()
                ->setSubject('Je vous ai envoyé un message !!!')
                ->setFrom('cryptyo@gmail.com')
                ->setTo('carpediemeuh@hotmail.com')
                ->setBody('test');

            $this->get('mailer')->send($sendMessage);




        return $this->render('GestyGestyBundle:Registration:register.html.twig');
    }

    public function foyerAction()
    {

        return $this->render('GestyGestyBundle:Profile:foyer.html.twig');
    }
}
