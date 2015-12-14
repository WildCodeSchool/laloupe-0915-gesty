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
    public function listLesEcureuilsAction()
    {

        $date = new \DateTime('now');
        $em = $this->getDoctrine()->getManager();
        $aujourdhui = $em->getRepository('WCSCantineBundle:Eleve')->findByDayLesEcureuils($date);

        return $this->render('WCSCantineBundle:Eleve:list.lesecureuils.html.twig', array(
            'aujourdhui' => $aujourdhui,
        ));

    }

    public function listRolandGarrosAction()
    {

        $date = new \DateTime('now');
        $em = $this->getDoctrine()->getManager();
        $aujourdhui = $em->getRepository('WCSCantineBundle:Eleve')->findByDayRolandGarros($date);

        return $this->render('WCSCantineBundle:Eleve:list.rolandgarros.html.twig', array(
            'aujourdhui' => $aujourdhui,
        ));

    }

    public function listNotreDameAction()
    {

        $date = new \DateTime('now');
        $em = $this->getDoctrine()->getManager();
        $aujourdhui = $em->getRepository('WCSCantineBundle:Eleve')->findByDayNotreDameDesFleurs($date);

        return $this->render('WCSCantineBundle:Eleve:list.notredame.html.twig', array(
            'aujourdhui' => $aujourdhui,
        ));

    }
}
