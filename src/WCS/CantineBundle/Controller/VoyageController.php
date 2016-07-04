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
use WCS\CantineBundle\Service\GestyScheduler\ActivityType;
use WCS\CantineBundle\Form\Type\VoyageType;


class VoyageController extends Controller
{
    public function inscrireAction(Request $request, Eleve $eleve)
    {
        //------------------------------------------------------------------------
        // inscriptions possible à partir de la date du jour + un délai de N jours
        // pour les voyages
        //------------------------------------------------------------------------
        /*
        $first_day_available = ActivityType::getFirstDayAvailable(
            ActivityType::TRAVEL,
            $this->get('wcs.datenow')->getDate()
        );
        */
        $first_day_available = $this->get('wcs.gesty.scheduler')->getFirstAvailableDate(
            $this->get('wcs.datenow')->getDate(),
            ActivityType::TRAVEL
            );

        // créer une instance d'un formulaire
        $options = array(
            'method' => 'POST',
            'action' => $this->generateUrl('voyage_inscription', array(
                'id' => $eleve->getId()
            )),
            'division' => $eleve->getDivision(),
            'date_day' => $first_day_available
        );

        $form = $this->createForm(new VoyageType(), $eleve, $options);


        // gère la requête du formulaire

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $eleve->setVoyageSigned(true);

            $em->persist($eleve);
            $em->flush();

            return $this->redirectToRoute('wcs_cantine_dashboard');
        }


        return $this->render("WCSCantineBundle:Voyage:inscription.html.twig", array(
            "eleve" => $eleve,
            "first_day_available" => $first_day_available,
            "form" => $form->createView()
        ));
    }
}
