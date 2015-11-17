<?php

namespace Gesty\GestyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestyGestyBundle:Default:index.html.twig');
    }

    public function registerAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('CryptYO@gmail.com')
            ->setTo('carpediemeuh@gmail.com')
            ->setBody('');

        $this->get('mailer')->send($message);


        return $this->render('GestyGestyBundle:Registration:register.html.twig');
    }
}
