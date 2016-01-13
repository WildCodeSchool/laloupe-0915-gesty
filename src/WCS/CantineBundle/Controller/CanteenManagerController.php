<?php
// src/WCS/CantineBundle/Controller/CanteenManagerController.php

namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use WCS\CantineBundle\Form\Type\LunchType;
use WCS\CantineBundle\Entity\Lunch;

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
    public function todayListAction(Request $request, $schoolId)
    {
        $em = $this->getDoctrine()->getManager();
        $eleves = $em->getRepository('WCSCantineBundle:Eleve')->getTodayList($schoolId);
        $schools = $em->getRepository('WCSCantineBundle:School')->find($schoolId);

        $lunch = new Lunch();
        $form = $this->createForm(new LunchType(), $lunch);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($lunch);
            $em->flush();

            return $this->redirect($this->generateUrl('wcs_gesty_ecoles'));
        }

        return $this->render('WCSCantineBundle:Eleve:todayList.html.twig', array(
            'eleves' => $eleves,
            'ecoles' => $schools,
            'form' => $form->createView(),
        ));

    }

}
