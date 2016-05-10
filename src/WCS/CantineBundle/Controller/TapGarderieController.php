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
        return $this->render(
            'WCSCantineBundle:TapGarderie:inscription.html.twig'
        );

    }

}