<?php

namespace WCS\CantineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use WCS\CalendrierBundle\Service\Calendrier\Calendrier;
use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\Lunch;
use WCS\CantineBundle\Form\Model\CantineFormEntity;
use WCS\CantineBundle\Form\Type\CantineType;



class CantineController extends Controller
{
    /**
     * @param Request $request
     * @param $id_eleve
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function inscrireAction(Request $request, $id_eleve)
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // récupère les instances de Doctrine et de Calendrier pour l'année en cours
        $em             = $this->getDoctrine()->getManager();
        $calendrier     = $this->get("wcs.calendrierscolaire")->getCalendrierRentreeScolaire();

        // récupère la fiche de l'élève sélectionné
        $eleve = $em->getRepository("WCSCantineBundle:Eleve")->find($id_eleve);

        if (!$eleve || !$eleve->isCorrectParentConnected($user)) {
            return $this->redirectToRoute('wcs_cantine_dashboard');
        }


        // récupère la liste des réservations effectuée
        // cette info est utile uniquement pour la gestion du formulaire dans Twig
        // En effet on doit supprimer toutes les réservations qui ne sont pas retournées
        // par le formulaire, hors les réservations effectuées, mais passées doivent être présentes
        // afin de ne pas être effacées de la base. Par ailleurs, on ne peut tout afficher
        // au risque du coup d'enregistrer des réservations qui n'ont pas été sélectionnées
        $listLunchesSelected = array();
        foreach($eleve->getLunches() as $lunch) {
            $listLunchesSelected[] = $lunch->getDate()->format("Y-m-d");
        }


        // récupère les jours fériés et met à jour le calendrier
        $feriesArray = $em->getRepository('WCSCantineBundle:Feries')->findListDateTimes(
                $calendrier->getPeriodesScolaire()->getAnneeScolaire()->getFin()->format('Y')
            );

        $calendrier->addDaysOff($feriesArray);

        // la réservation à la cantine ne peut être effectué que 7 jours après
        // la date du jour (soit le 8e jour)
        // on désactive donc le jour actuel et les 7 jours suivants.
        $oneDay     = new \DateInterval('P1D');
        $currentDay = new \DateTimeImmutable($calendrier->getDateToday());
        $dayPlus7   = $currentDay->add(new \DateInterval('P8D'));

        while ($currentDay < $dayPlus7) {
            $cantineWeekLocked[] = $currentDay;
            $currentDay = $currentDay->add($oneDay);
        }
        $calendrier->addDaysPast($cantineWeekLocked);



        // créé le formulaire associé à l'élève
        $form = $this->createForm(new CantineType( $em ), $eleve, array(
            'action' => $this->generateUrl('cantine_inscription', array("id_eleve"=>$id_eleve)),
            'method' => 'POST'
        ));

        // traite les infos saisies dans le formulaire
        if ($this->processPostedForm($request, $form, $eleve)) {
            return $this->redirect($this->generateUrl('wcs_cantine_dashboard'));
        }


        // génère la vue avec les paramètres attendus
        return $this->render(
            'WCSCantineBundle:Cantine:inscription.html.twig',
            array(
                "form" => $form->createView(),
                "eleve" => $eleve,
                "calendrier" => $calendrier,
                "listLunchesSelected" => $listLunchesSelected
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
            return true;
        }

        return false;
    }
}
