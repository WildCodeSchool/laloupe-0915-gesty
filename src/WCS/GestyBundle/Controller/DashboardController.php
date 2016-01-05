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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirect($this->generateUrl('wcs_gesty_ecoles'));
        }
        return $this->render('WCSGestyBundle:Dashboard:index.html.twig');

    }
}
