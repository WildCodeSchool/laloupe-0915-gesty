<?php

namespace GestyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GestyBundle:Default:index.html.twig', array('name' => $name));
    }
}
