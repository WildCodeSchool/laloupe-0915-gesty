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
        return $this->render('GestyGestyBundle:Registration:register.html.twig');

    }

}
