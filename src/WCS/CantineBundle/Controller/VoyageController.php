<?php
/**
 * Created by PhpStorm.
 * User: manu
 * Date: 10/05/16
 * Time: 11:13
 */

namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \WCS\CantineBundle\Entity\Eleve;


class VoyageController extends Controller
{
    public function inscrireAction($id_eleve)
    {
        // récupère une instance de Doctrine
        $em = $this->getDoctrine()->getManager();
        
        
        

        // récupère la liste des voyages, classés par ordre alphabétique
        $voyages = $em->getRepository("WCSCantineBundle:Voyage")->findBy(array(), array("date_debut"=>"ASC"));

        // génère la vue, en passant le tableau d'objets "Voyage" comme paramètre
        return $this->render(
            'WCSCantineBundle:Voyage:inscription.html.twig',
            array("voyages" => $voyages)
        );

    }

}