<?php
/**
 * Created by PhpStorm.
 * User: manu
 * Date: 10/05/16
 * Time: 11:09
 */

namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \WCS\CantineBundle\Entity\Eleve;

class TapGarderieController extends Controller
{
    public function inscrireAction($id_eleve)
    {
        // récupère une instance de Doctrine
        $em = $this->getDoctrine()->getManager();




        // récupère la liste des enfants
        $eleve = $em->getRepository("WCSCantineBundle:Eleve")->findOneBy(array("id"=>$id_eleve));
       // var_dump($eleve);
       // die();
        //
        return $this->render(
            'WCSCantineBundle:TapGarderie:inscription.html.twig',
            array("eleve" => $eleve)
        );

    }

  

}