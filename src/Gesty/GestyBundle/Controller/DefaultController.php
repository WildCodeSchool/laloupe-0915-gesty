<?php

namespace Gesty\GestyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GestyGestyBundle:Default:index.html.twig', array('name' => $name));
    }
}
