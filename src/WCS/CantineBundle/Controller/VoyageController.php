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
use \WCS\CantineBundle\Entity\Voyage;



class VoyageController extends Controller
{
    public function inscrireAction($id_eleve)
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // récupère une instance de Doctrine
        $em = $this->getDoctrine()->getManager();

        // récupère la fiche élève depuis la base de données
        $eleve = $em->getRepository("WCSCantineBundle:Eleve")->findOneBy(array('id'=>$id_eleve));
        if (!$eleve || !$eleve->isCorrectParentConnected($user)) {
            return $this->redirectToRoute('wcs_cantine_dashboard');
        }

        //récupère la liste de tous les voyages scolaires depuis la base de données
        $voyages =$em->getRepository("WCSCantineBundle:Voyage")->findByDivisionsAnneeScolaire(
            array("division" => $eleve->getDivision())
        );


        return $this->render( "WCSCantineBundle:Voyage:inscription.html.twig", array(
            "eleve" => $eleve,
            "voyages" => $voyages
        ));
    }

}