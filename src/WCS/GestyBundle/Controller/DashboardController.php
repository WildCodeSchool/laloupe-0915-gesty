<?php

namespace WCS\GestyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        if (!$user)
        {
            return $this->redirect($this->generateUrl('sonata_user_security_login'));
        }
        return $this->render('WCSGestyBundle:Dashboard:index.html.twig');
    }


    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('ApplicationSonataUserBundle:User')->findAll();



        return $this->render('WCSGestyBundle:Dashboard:dashboard.html.twig', array(
            'entities' => $entities, 
        ));


    }
}
