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
            return $this->redirectToRoute('sonata_user_security_login');
        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute('wcs_employee_home');
        }
        return $this->redirectToRoute('wcs_cantine_dashboard');
    }
}
