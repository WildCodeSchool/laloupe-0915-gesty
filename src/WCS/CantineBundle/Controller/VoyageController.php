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
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\Type\VoyageType;


class VoyageController extends Controller
{
    public function inscrireAction(Request $request, Eleve $eleve)
    {
        // créer une instance d'un formulaire
        $options = array(
            'method' => 'POST',
            'action' => $this->generateUrl('voyage_inscription', array(
                'id' => $eleve->getId()
            )),
            'division' => $eleve->getDivision(),
            'date_day' => $this->get('wcs.datenow')->getDate()
        );

        $form = $this->createForm(new VoyageType(), $eleve, $options);


        // gère la requête du formulaire

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
