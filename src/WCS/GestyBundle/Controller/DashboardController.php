<?php

namespace WCS\GestyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        $role = $this->get('security.authorization_checker');

        $user = $this->getUser();
        if (!$user)
        {
            return $this->redirectToRoute('sonata_user_security_login');
        }
        if ($role->isGranted('ROLE_ADMIN') ||
            $role->isGranted('ROLE_CANTINE') ||
            $role->isGranted('ROLE_TAP') ||
            $role->isGranted('ROLE_GARDERIE'))
        {
            return $this->redirectToRoute('wcs_employee_home');
        }
        return $this->redirectToRoute('wcs_cantine_dashboard');
    }
}
