<?php
namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use \WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\Type\TapType;
use WCS\CalendrierBundle\Service\Calendrier\Day;


class TapGarderieController extends Controller
{
    public function inscrireAction(Request $request, $id_eleve)
    {
        // récupère l'utilisateur actuellement connecté
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // récupère une instance de Doctrine
        $em = $this->getDoctrine()->getManager();

        // récupère un  enfants
        $eleve = $em->getRepository("WCSCantineBundle:Eleve")->find($id_eleve);
        if (!$eleve || !$eleve->isCorrectParentConnected($user)) {
            return $this->redirectToRoute('wcs_cantine_dashboard');
        }

        // récupère la période scolaires en classe à la date du jour
        $periodesScolaires = $this->get("wcs.calendrierscolaire")->getPeriodesAnneeRentreeScolaire();
        $periode = $periodesScolaires->findEnClasseFrom(new \DateTime());

        // créé le formulaire associé à l'élève
        $form = $this->createForm(
                    new TapType( $em, $periode ),
                    $eleve, array(
            'action' => $this->generateUrl('tapgarderie_inscription', array("id_eleve"=>$id_eleve)),
            'method' => 'POST'
        ));

        // traite les infos saisies dans le formulaire
        if ($this->processPostedForm($request, $form, $eleve)) {
            return $this->redirect($this->generateUrl('wcs_cantine_dashboard'));
        }


        return $this->render(
            'WCSCantineBundle:TapGarderie:inscription.html.twig',
            array(
                "eleve" => $eleve,
                "periode_tap" => $periode,
                "form" => $form->createView()
                )
        );
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param Eleve $eleve
     * @return bool
     */
    private function processPostedForm(Request $request, Form $form, Eleve $eleve)
    {
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isValid()) {

            $tapCurrents = $em->getRepository("WCSCantineBundle:Tap")->findByEleve($eleve);
            foreach($tapCurrents as $tap) {
                $em->remove($tap);
            }

            $garderieCurrents = $em->getRepository("WCSCantineBundle:Garderie")->findByEleve($eleve);
            foreach($garderieCurrents as $garderie) {
                $em->remove($garderie);
            }

            $em->flush();

/*
            // la nouvelle sélection de dates (avec celles déjà présentes en
            // base de données, et les nouvelles à ajouter
            // (cette liste a été mise à jour avec LunchToStringTransformer)
            $lunchesNew = $eleve->getLunches();

            // récupère les réservations actuellement en base de données
            $lunchesOld = $em->getRepository("WCSCantineBundle:Lunch")->findByEleve($eleve);

            // supprime les dates qui ne sont plus sélectionnées
            foreach($lunchesOld as $lunchOld) {
                if (!$lunchesNew->contains($lunchOld)) {
                    $em->remove($lunchOld);
                }
            }

            // met à jour la fiche élève (le régime alimentaire,...)
            $em->persist($eleve);

            $em->flush();
*/
            return true;
        }

        return false;
    }
}