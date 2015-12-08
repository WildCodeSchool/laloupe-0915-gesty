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

        $entities = $em->getRepository('WCSCantineBundle:Eleve')->findBy(array(
            'dates' => preg_match('/[0-9]{4}-[0-9]{1}-[0-9]{1};/','#2015-9-8;#')
        ));

        return $this->render('WCSCantineBundle:Eleve:list.html.twig', array(
            'entities' => $entities,
        ));
    }

}
