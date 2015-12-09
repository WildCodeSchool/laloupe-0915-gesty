<?php

namespace WCS\CantineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use WCS\CantineBundle\Entity\Eleve;


/**
 * List controller.
 *
 */
class ListInscritsController extends Controller
{

    /**
     * Lists all Eleve entities.
     *
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('WCSCantineBundle:Eleve')->findByDay(new \DateTime('2016-02-04'));

        return $this->render('WCSCantineBundle:Eleve:list.html.twig', array(
            'entities' => $entities,
        ));
    }

}
