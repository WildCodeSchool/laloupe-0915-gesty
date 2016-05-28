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
use \WCS\CantineBundle\Entity\Eleve;
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
        // dans un premiér temps on definit les paramêtres de notre futur FORM en Html
        // ensuite on créer une instance de formulaire avec les paramêtre que l'on a crée
        // et que l'on passera a notre vue twig

        $formparams = array(
            'method' => 'POST',
            'action' => $this->generateUrl('voyage_inscription', array(
                'id_eleve' => $id_eleve
            ))
        );

        $form = $this->createForm(new VoyageType($eleve->getDivision()), $eleve, $formparams);


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
