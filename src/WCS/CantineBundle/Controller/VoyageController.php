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
use WCS\CantineBundle\Form\Type\VoyageType;


class VoyageController extends Controller
{
    public function inscrireAction(Request $request,$id_eleve)
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // récupère une instance de Doctrine
        $em = $this->getDoctrine()->getManager();

        // récupère la fiche élève depuis la base de données
        $eleve = $em->getRepository("WCSCantineBundle:Eleve")->find($id_eleve);
        if (!$eleve || !$eleve->isCorrectParentConnected($user)) {
            return $this->redirectToRoute('wcs_cantine_dashboard');
        }

        // créer une instance d'un formulaire

        $options = array(
            'method' => 'POST',
            'action' => $this->generateUrl('voyage_inscription', array(
                'id_eleve' => $id_eleve
            )),
            'division' => $eleve->getDivision(),
            'date_day' => $this->get('wcs.datenow')->getDate()
        );

        $form = $this->createForm(new VoyageType(), $eleve, $options);


        // gère la requête du formulaire

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($eleve);
            $em->flush();

            return $this->redirectToRoute('wcs_cantine_dashboard');
        }


        return $this->render("WCSCantineBundle:Voyage:inscription.html.twig", array(
            "eleve" => $eleve,
            "form" => $form->createView()
        ));
    }
}
