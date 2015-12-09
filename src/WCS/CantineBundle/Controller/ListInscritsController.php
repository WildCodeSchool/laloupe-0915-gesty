<?php

namespace WCS\CantineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Controller\EleveController as Pupils;


/**
 * List controller.
 *
 */
class ListInscritsController extends Pupils
{

    /**
     * Lists all Eleve entities.
     *
     */
    public function listAction()
    {
        $interval = new \DateInterval(strtotime('next Monday'));

        $date = new \DateTime('now');
        $date->add($interval);
        $em = $this->getDoctrine()->getManager();
        $aujourdhui = $em->getRepository('WCSCantineBundle:Eleve')->findByDay($date);

        return $this->render('WCSCantineBundle:Eleve:list.html.twig', array(
            'aujourdhui' => $aujourdhui,
        ));

    }

}
