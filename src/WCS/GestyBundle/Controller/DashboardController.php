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

}
