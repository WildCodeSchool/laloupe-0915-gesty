<?php

namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * List controller.
 *
 */
class ListInscritsController extends Controller
{

    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $schools = $em->getRepository('WCSCantineBundle:School')->findAll();

        return $this->render('WCSCantineBundle:DameCantine:index.html.twig', array(
            'schools' => $schools,
        ));
    }

    /**
     * Lists all Eleve entities.
     *
     */
    public function todayListAction($school)
    {
        $em = $this->getDoctrine()->getManager();
        $eleves = $em->getRepository('WCSCantineBundle:Eleve')->getTodayList($school);

        return $this->render('WCSCantineBundle:Eleve:todayList.html.twig', array(
            'eleves' => $eleves,
        ));

    }
}
