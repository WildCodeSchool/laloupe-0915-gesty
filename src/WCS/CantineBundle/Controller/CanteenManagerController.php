<?php
// src/WCS/CantineBundle/Controller/CanteenManagerController.php

namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * List controller.
 *
 */
class CanteenManagerController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $schools = $em->getRepository('WCSCantineBundle:School')->findAll();

        return $this->render('WCSCantineBundle:CanteenManager:index.html.twig', array(
            'schools' => $schools,
        ));
    }

    /**
     * Lists all Eleve entities.
     *
     */
    public function todayListAction($schoolId)
    {
        $em = $this->getDoctrine()->getManager();
        $eleves = $em->getRepository('WCSCantineBundle:Eleve')->getTodayList($schoolId);
        $schools = $em->getRepository('WCSCantineBundle:School')->find($schoolId);

        return $this->render('WCSCantineBundle:Eleve:todayList.html.twig', array(
            'eleves' => $eleves,
            'ecoles' => $schools,
        ));

    }
}
